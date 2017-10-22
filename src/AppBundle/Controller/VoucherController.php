<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Payment;
use AppBundle\Entity\Voucher;
use AppBundle\Event\AppEvents;
use AppBundle\Event\VoucherCreatedEvent;
use AppBundle\Form\Type\CommentType;
use AppBundle\Form\Type\SearchVoucherType;
use AppBundle\Form\Type\TreatmentVoucherType;
use AppBundle\Form\Type\ValueVoucherType;
use AppBundle\Form\Type\VoucherDateType;
use AppBundle\Form\Type\VoucherTypeType;
use AppBundle\Form\Type\VoucherUseType;
use AppBundle\Service\VoucherFinder;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        if ($voucher->getCreationDate()) {
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
            $this->get('event_dispatcher')->dispatch(
                AppEvents::VOUCHER_CREATED,
                new VoucherCreatedEvent($voucher, $form)
            );

            $em = $this->getDoctrine()->getManager();
            $em->persist($voucher);
            $em->flush();

            return $this->redirectToRoute('voucher_preview', [
                'id' => $voucher->getId()
            ]);
        }

        return [
            'form' => $form->createView(),
            'voucher' => $voucher
        ];
    }

    /**
     * @Template("voucher/create_treatment.html.twig")
     */
    public function createTreatmentVoucherAction(Request $request, Voucher $voucher)
    {
        $form = $this
            ->createForm(TreatmentVoucherType::class, $voucher)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('event_dispatcher')->dispatch(
                AppEvents::VOUCHER_CREATED,
                new VoucherCreatedEvent($voucher, $form)
            );

            $em = $this->getDoctrine()->getManager();
            $em->persist($voucher);
            $em->flush();

            return $this->redirectToRoute('voucher_preview', [
                'id' => $voucher->getId()
            ]);
        }

        return [
            'form' => $form->createView(),
            'voucher' => $voucher
        ];
    }

    /**
     * @Route("/voucher/create/{id}", name="voucher_create")
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
    }

    /**
     * @Route("/voucher/preview/{id}", name="voucher_preview")
     * @Template("voucher/preview.html.twig")
     */
    public function previewVoucherAction(Voucher $voucher)
    {
        return [
            'voucher' => $voucher
        ];
    }

    /**
     * @Route("/voucher/edit/{id}", name="voucher_edit")
     */
    public function editVoucherAction(Request $request, Voucher $voucher) : Response
    {
        if (!$voucher->getPayments()->isEmpty()) {
            return $this->redirectToRoute('voucher_search');
        }

        return $this->forward(
            $voucher->getType()->getId() === 1
                ? 'AppBundle:Voucher:editValueVoucher'
                : 'AppBundle:Voucher:editTreatmentVoucher',
            [
                'voucher' => $voucher,
                'parent' => $this->getParentData($request)
            ]
        );
    }

    /**
     * @Template("voucher/edit_value.html.twig")
     */
    public function editValueVoucherAction(Request $request, Voucher $voucher, array $parent)
    {
        $form = $this
            ->createForm(ValueVoucherType::class, $voucher)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('event_dispatcher')->dispatch(
                AppEvents::VOUCHER_UPDATED,
                new VoucherCreatedEvent($voucher, $form)
            );
            $em = $this->getDoctrine()->getManager();
            $em->persist($voucher);
            $em->flush();

            return $this->redirectToRoute($parent['parentRoute'], $parent);
        }

        return array_merge($parent,['form' => $form->createView(), 'voucher' => $voucher,]);
    }

    /**
     * @Template("voucher/edit_treatment.html.twig")
     */
    public function editTreatmentVoucherAction(Request $request, Voucher $voucher, array $parent)
    {
        $form = $this
            ->createForm(TreatmentVoucherType::class, $voucher)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('event_dispatcher')->dispatch(
                AppEvents::VOUCHER_CREATED,
                new VoucherCreatedEvent($voucher, $form)
            );
            $em = $this->getDoctrine()->getManager();
            $em->persist($voucher);
            $em->flush();

            return $this->redirectToRoute($parent['parentRoute'], $parent);
        }

        return array_merge($parent,['form' => $form->createView(), 'voucher' => $voucher,]);
    }

    /**
     * @Route("/voucher/save/{id}", name="voucher_save")
     */
    public function saveVoucherAction() : Response
    {
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

        $form = $this
            ->createForm(VoucherDateType::class)
            ->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \DateTime $filterFrom */
            $filterFrom = $form->get('filterFrom')->getData();
            if ($filterFrom !== null) {
                $filterFrom->setTime(0,0);
            }
            /** @var \DateTime $filterTo */
            $filterTo = $form->get('filterTo')->getData();
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
