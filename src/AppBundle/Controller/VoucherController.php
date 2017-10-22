<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Payment;
use AppBundle\Entity\Voucher;
use AppBundle\Form\Type\CommentType;
use AppBundle\Form\Type\SearchVoucherType;
use AppBundle\Form\Type\ValueVoucherType;
use AppBundle\Form\Type\VoucherDateType;
use AppBundle\Form\Type\VoucherType;
use AppBundle\Form\Type\VoucherTypeType;
use AppBundle\Form\Type\VoucherUseType;
use AppBundle\Service\VoucherFinder;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class VoucherController
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 */
class VoucherController extends Controller
{
    /**
     * @Route("/voucher/search", name="voucher_search")
     */
    public function searchVoucherAction(Request $request) : Response
    {
        if (!$request->get('page')) {
            $request->request->set('page', 1);
        }

        $searchForm = $this->createForm(SearchVoucherType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isValid()) {
            $request->request->set('voucherCode', $searchForm->getData()['vouchercode']);
        }

        $filters = [
            'page' => (int)$request->get('page'),
            'items_per_page' => $this->getParameter('vouchers_per_page'),
            'voucherCode' => $request->get('voucherCode') === null ? '-1' : $request->get('voucherCode'),
            'decreasing' => true,
        ];

        $allVouchersCount = $this->getDoctrine()
            ->getRepository('AppBundle:Voucher')
            ->countAllWithCode($request->get('voucherCode'));

        $vouchers = $this->get(VoucherFinder::class)->setFilters($filters)->getVouchers();
        $nrOfPages = (int)($allVouchersCount / $this->getParameter('vouchers_per_page')) + 1;
        if ($allVouchersCount % $this->getParameter('vouchers_per_page') == 0 || $allVouchersCount == 0) {
            $nrOfPages = $allVouchersCount / $this->getParameter('vouchers_per_page');
        }

        return $this->render('floathamburg/vouchersearch.html.twig', [
            'searchForm' => $searchForm->createView(),
            'matchedVouchers' => $vouchers,
            'numberOfPages' => $nrOfPages,
            'currentPage' => $request->get('page'),
            'searchedCode' => $request->get('voucherCode'),
            'voucherCode' => $request->get('voucherCode'),
        ]);
    }

    /**
     * @Route("/voucher/{id}/delete", name="voucher_delete")
     */
    public function deleteVoucher(Voucher $voucher): Response
    {
        if (!$voucher->isBlocked()) {
            throw new \LogicException("Can't delete an active voucher!");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($voucher);
        $em->flush();

        return $this->redirectToRoute('voucher_chose_type');
    }

    /**
     * @Route("/voucher/chose-type", name="voucher_chose_type")
     * @Template("voucher/create_step_1.html.twig")
     */
    public function createChoseTypeAction(Request $request)
    {
        $voucher = new Voucher();

        $form = $this
            ->createForm(VoucherTypeType::class, $voucher)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $voucher->setCreationDate(new \DateTime());
            $voucher->setBlocked(true);

            $em = $this->getDoctrine()->getManager();
            $em->persist($voucher);
            $em->flush();

            return $this->redirectToRoute('voucher_create', [
                'id' => $voucher->getId()
            ]);
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Template("voucher/create_value.html.twig")
     */
    public function createValueVoucherAction(Request $request, Voucher $voucher)
    {
        $form = $this
            ->createForm(ValueVoucherType::class, $voucher)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $voucher->setBlocked(false);
            $this->fillVoucherDetails($voucher);

            $em = $this->getDoctrine()->getManager();
            $em->persist($voucher);
            $em->flush();

            return $this->redirectToRoute('voucher_all');
        }

        return [
            'form' => $form->createView(),
            'voucher' => $voucher
        ];
    }

    /**
     * @Template("voucher/create_treatment.html.twig")
     */
    public function createTreatmentVoucherAction(Request $request, Voucher $voucher): array
    {
        // TODO: implement treatment vouchers using the same principle as value vouchers.
        return [];
    }

    /**
     * @Route("/voucher/create/{id}", name="voucher_create")
     *
     * TODO: This method should only forward the request depending on the type of voucher.
     */
    public function createVoucherAction(Voucher $voucher) : Response
    {
        return $this->forward(
            $voucher->getType()->getId() === 1
                ? 'AppBundle:Voucher:createValueVoucher'
                : 'AppBundle:Voucher:createTreatmentVoucher',
            [
                'voucher' => $voucher
            ]
        );


        if ($this->get('session')->get('voucher')) {
            $this->get('session')->remove('voucher');
        }

        $form = $this->createForm(VoucherType::class, $voucher);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->fillVoucherDetails($voucher);
            $voucher->setCreationDate(new DateTime());
            $this->calculateVoucherCode($form['voucherCodeLetter']->getData(), $voucher);
            $this->get('session')->set('voucher', $voucher);
            $this->prepareVoucherUsages($voucher, $form);
            return $this->render('floathamburg/vouchercreate.html.twig', array(
                'form' => null,
                'submitted' => true,
                'voucher' => $voucher,
                'shops' => $this->getDoctrine()->getRepository('AppBundle:Shop')->findAll(),
            ));
        }

        return $this->render('floathamburg/vouchercreate.html.twig', array(
            'form' => $form->createView(),
            'submitted' => false,
            'voucher' => null,
        ));
    }

    /**
     * @Route("/voucher/create/edit/{id}", name="voucher_edit")
     *
     * @ParamConverter("voucher", class="AppBundle:Voucher")
     */
    public function editVoucherAction(Request $request, Voucher $voucher) : Response
    {
        if (!$voucher->getPayments()->isEmpty()) {
            return $this->redirectToRoute('voucher_search');
        }

        $parent = $this->getParentData($request);
        $form = $this->createForm(VoucherType::class, $voucher);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($voucher);
            $em->flush();

            return $this->redirectToRoute($parent['parentRoute'], [
                'page' => $parent['page'],
                'filterFrom' => $parent['filterFrom'],
                'filterTo' => $parent['filterTo'],
                'voucherCode' => $parent['voucherCode'],
            ]);
        }

        return $this->render('floathamburg/voucheredit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    protected function prepareVoucherUsages(Voucher $voucher, Form $form)
    {
        $usages = $voucher->getUsages();
        foreach ($usages as $key=>$usage) {
            if ($usage === 'massage') {
                $usages[$key] .= ' '.$form['massage_type']->getData().' '.$form['time_for_massage']->getData();
            } else if ($usage === 'floating') {
                $usages[$key] .= ' '.$form['time_for_floating']->getData();
            }
        }

        $voucher->setUsages($usages);
    }

    /**
     * @Route("/voucher/save", name="voucher_save")
     */
    public function saveVoucherAction() : Response
    {
        if (!$this->get('session')->get('voucher')) {
            return $this->redirectToRoute('voucher_create');
        }

        $voucher = $this->get('session')->get('voucher');
        $this->fillVoucherDetails($voucher);

        $em = $this->getDoctrine()->getManager();
        $em->persist($voucher);
        $em->flush();

        $this->get('session')->remove('voucher');

        return $this->render('floathamburg/vouchersavedsuccessfully.html.twig');
    }

    /**
     * @Route("/voucher/all", name="voucher_all")
     */
    public function vouchersAction(Request $request) : Response
    {
        if (!$request->get('page')) {
            $request->request->set('page', 1);
        }

        $filterFrom = null;
        if ($request->get('filterFrom') !== null) {
            $filterFrom = new \DateTime($request->get('filterFrom'). ' 00:00');
        }
        $filterTo = null;
        if ($request->get('filterTo') !== null) {
            $filterTo = new \DateTime($request->get('filterTo').' 23:59');
        }

        $form = $this->createForm(VoucherDateType::class);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $filterFrom = $form->getData()['filterFrom'];
            if ($filterFrom !== null) {
                $filterFrom->setTime(0,0);
            }
            $filterTo = $form->getData()['filterTo'];
            if ($filterTo !== null) {
                $filterTo->setTime(23,59);
            }
            $request->query->remove('page');
            $request->request->set('page', 1);
        }

        $filters = [
            'page' => (int)$request->get('page'),
            'items_per_page' => $this->getParameter('vouchers_per_page'),
            'decreasing' => true,
        ];
        $voucherFinder = $this->get(VoucherFinder::class);
        if ($filterFrom !== null) {
            $filters['filterFrom'] = $filterFrom;
        }
        if ($filterTo !== null) {
            $filters['filterTo'] = $filterTo;
        }

        $vouchers = $voucherFinder->setFilters($filters)->getVouchers();
        $allVouchersCount = $this->getDoctrine()->getRepository('AppBundle:Voucher')->countAll($filterFrom, $filterTo);
        $nrOfPages = (int)($allVouchersCount / $this->getParameter('vouchers_per_page')) + 1;
        if ($allVouchersCount % $this->getParameter('vouchers_per_page') == 0 || $allVouchersCount == 0) {
            $nrOfPages = $allVouchersCount / $this->getParameter('vouchers_per_page');
        }

        return $this->render('floathamburg/vouchers.html.twig',[
            'vouchers' => $vouchers,
            'numberOfPages' => $nrOfPages,
            'currentPage' => $request->get('page'),
            'filterFrom' => $filterFrom !== null ? $filterFrom->format('Y-m-d') : null,
            'filterTo' => $filterTo !== null ? $filterTo->format('Y-m-d') : null,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/voucher/all/reset-filters", name="voucher_all_reset_filters")
     */
    public function resetFiltersAction() : RedirectResponse
    {
        return $this->redirectToRoute('voucher_all', ['page' => 1]);
    }

    /**
     * @Route("/voucher/{id}", name="voucher_details", requirements={"id": "\d+"})
     * @ParamConverter("voucher", class="AppBundle:Voucher")
     * @Template("floathamburg/voucher_details.html.twig")
     */
    public function voucherAction(Voucher $voucher) : array
    {
        return ['voucher' => $voucher];
    }

    /**
     * @Route("/voucher/use/{id}", name="voucher_use")
     */
    public function useVoucherAction(Request $request, Voucher $voucher = null) : Response
    {
        if ($voucher === null || $voucher->isBlocked()) {
            return $this->redirectToRoute('voucher_search');
        }

        $form = $this->createForm(VoucherUseType::class, null, [
            'voucherUsages' => $voucher->getUsages(),
            'remainingVoucherValue' => $voucher->getRemainingValue()
        ]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->savePaymentDetails($voucher, $em, $form->getData());
            $em->persist($voucher);
            $em->flush();

            return $this->render('floathamburg/voucheruse.html.twig', [
                'form' => null,
                'submitted' => true,
                'voucher' => $voucher
            ]);
        }

        return $this->render('floathamburg/voucheruse.html.twig', [
            'form' => $form->createView(),
            'submitted' => false,
            'voucher' => $voucher
        ]);
    }

    protected function savePaymentDetails(Voucher $voucher, ObjectManager $em, array $formData)
    {
        $payment = new Payment();
        $product = '';
        foreach ($formData['used_for'] as $usage) {
            if ($usage == 'USE_FOR_MASSAGE') {
                $product .= 'Massage ';
            }
            if ($usage == 'USE_FOR_FLOAT') {
                $product .= 'Floating ';
            }
        }

        $payment->setProduct($product);
        $payment->setVoucherBought($voucher);
        if ($formData['usage'] == 'COMPLETE_USE') {
            $payment->setAmount($voucher->getRemainingValue());
        } else {
            $payment->setAmount($formData['partial_amount']);
        }
        $payment->setEmployee($this->getUser());
        $payment->setPaymentDate(new \DateTime());

        $voucher->setPartialPayment($voucher->getPartialPayment() + $payment->getAmount());
        $voucher->setRemainingValue($voucher->getRemainingValue() - $payment->getAmount());

        $em->persist($payment);
        $em->flush();
    }

    /**
     * TODO: This should be handled by a doctrine listener on persist.
     */
    protected function fillVoucherDetails(Voucher $voucher)
    {
        $voucher->setShopWhereCreated($this->getUser()->getShop());
        $voucher->setAuthor($this->getUser());
    }

    /**
     * @Route("/voucher/block/{id}", name="voucher_block")
     *
     * @ParamConverter("voucher", class="AppBundle:Voucher")
     */
    public function blockVoucherAction(Voucher $voucher, Request $request) : RedirectResponse
    {
        $parent = $this->getParentData($request);
        $voucher->setBlocked(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($voucher);
        $em->flush();

        return $this->redirectToRoute($parent['parentRoute'], [
            'page' => $parent['page'],
            'filterFrom' => $parent['filterFrom'],
            'filterTo' => $parent['filterTo'],
            'voucherCode' => $parent['voucherCode'],
        ]);
    }

    /**
     * @Route("/voucher/comment/{id}", name="voucher_comment_edit")
     */
    public function editVoucherCommentAction(Voucher $voucher, Request $request) : Response
    {
        $parent = $this->getParentData($request);
        $form = $this->createForm(CommentType::class, $voucher);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($voucher);
            $em->flush();

            return $this->redirectToRoute($parent['parentRoute'], [
                'page' => $parent['page'],
                'filterFrom' => $parent['filterFrom'],
                'filterTo' => $parent['filterTo'],
                'voucherCode' => $parent['voucherCode'],
            ]);
        }

        return $this->render('floathamburg/vouchercomment.html.twig', [
            'form' => $form->createView(),
            'submitted' => false,
            'voucher' => $voucher,
        ]);
    }

    protected function calculateVoucherCode(string $voucherLetter, Voucher $voucher)
    {
        $shopToCodeMap = [
            0 => 222,
            1 => 201,
            2 => 204,
            3 => 205
        ];

        $shopId = $this->getUser()->getShop()->getId();
        if ($voucher->isOnlineVoucher()) {
            $shopId = 0;
        }
        $voucherCodeInfo = $this->getDoctrine()->getRepository('AppBundle:VoucherCodeInformation')->find($shopId);

        $voucher->setVoucherCode($shopToCodeMap[$shopId].$voucherLetter.$voucherCodeInfo->getNextVoucherCode());
        $em = $this->getDoctrine()->getManager();
        $em->persist($voucherCodeInfo);
        $em->flush();
    }

    protected function getParentData(Request $request) : array
    {
        return [
            'parentRoute' => $request->query->get('parentRoute', 'voucher_search'),
            'page' => $request->query->get('page', null),
            'filterFrom' => $request->query->get('filterFrom', null),
            'filterTo' => $request->query->get('filterTo', null),
            'voucherCode' => $request->query->get('voucherCode', null)
        ];
    }
}
