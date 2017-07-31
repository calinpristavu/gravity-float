<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Payment;
use AppBundle\Entity\Shop;
use AppBundle\Entity\Voucher;
use AppBundle\Form\Type\SearchVoucherType;
use AppBundle\Form\Type\VoucherDateType;
use AppBundle\Form\Type\VoucherType;
use AppBundle\Form\Type\VoucherUseType;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class VoucherController
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 */
class VoucherController extends Controller
{
    public static $NUMBER_OF_VOUCHERS_PER_PAGE = 5;

    /**
     * @Route("/voucher/search", name="voucher_search")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchVoucherAction(Request $request)
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
            'items_per_page' => self::$NUMBER_OF_VOUCHERS_PER_PAGE,
            'voucherCode' => $request->get('voucherCode') === null ? '-1' : $request->get('voucherCode'),
        ];

        $allVouchersCount = $this->getDoctrine()->getRepository('AppBundle:Voucher')->countAll();
        if ($request->get('voucherCode') !== null) {
            $allVouchersCount = $this->getDoctrine()
                ->getRepository('AppBundle:Voucher')
                ->countAllWithCode($request->get('voucherCode'));
        }

        $vouchers = $this->get('voucher.finder')->setFilters($filters)->getVouchers();
        $nrOfPages = (int)($allVouchersCount / self::$NUMBER_OF_VOUCHERS_PER_PAGE) + 1;
        if ($allVouchersCount % self::$NUMBER_OF_VOUCHERS_PER_PAGE == 0) {
            $nrOfPages = $allVouchersCount / self::$NUMBER_OF_VOUCHERS_PER_PAGE;
        }

        return $this->render('floathamburg/vouchersearch.html.twig', [
            'searchForm' => $searchForm->createView(),
            'matchedVouchers' => $vouchers,
            'numberOfPages' => $nrOfPages,
            'currentPage' => $request->get('page'),
            'searchedCode' => $request->get('voucherCode'),
        ]);
    }

    /**
     * @Route("/voucher/create", name="voucher_create")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createVoucherAction(Request $request)
    {
        if ($this->get('session')->get('voucher')) {
            $this->get('session')->remove('voucher');
        }

        $voucher = new Voucher();

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
     * @Route("/voucher/create/edit", name="voucher_edit")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editVoucherAction(Request $request)
    {
        if (!$this->get('session')->get('voucher')) {
            return $this->redirectToRoute('voucher_create');
        }

        $voucher = $this->get('session')->get('voucher');
        $this->fillVoucherDetails($voucher);
        $form = $this->createForm(VoucherType::class, $voucher);
        $form->handleRequest($request);
        if ($form->isValid()) {
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
     * @param Voucher $voucher
     * @param Form $form
     */
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
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveVoucherAction()
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
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function vouchersAction(Request $request)
    {
        if (!$request->get('page')) {
            $request->request->set('page', 1);
        }

        $form = $this->createForm(VoucherDateType::class);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $request->request->set('filterFrom', $form->getData()['filterFrom']->format('Y-m-d'));
            $request->request->set('filterTo', $form->getData()['filterTo']->format('Y-m-d'));
        }

        $filters = [
            'page' => (int)$request->get('page'),
            'items_per_page' => self::$NUMBER_OF_VOUCHERS_PER_PAGE,
        ];

        $voucherFinder = $this->get('voucher.finder');
        if ($request->get('filterFrom') !== null && $request->get('filterTo') !== null) {
            $filters['filterFrom'] = $request->get('filterFrom');
            $filters['filterTo'] = $request->get('filterTo');
        }

        $vouchers = $voucherFinder->setFilters($filters)->getVouchers();
        $allVouchersCount = $this->getDoctrine()
            ->getRepository('AppBundle:Voucher')
            ->countAll($request->get('filterFrom'), $request->get('filterTo'));
        $nrOfPages = (int)($allVouchersCount / self::$NUMBER_OF_VOUCHERS_PER_PAGE) + 1;
        if ($allVouchersCount % self::$NUMBER_OF_VOUCHERS_PER_PAGE == 0) {
            $nrOfPages = $allVouchersCount / self::$NUMBER_OF_VOUCHERS_PER_PAGE;
        }

        return $this->render('floathamburg/vouchers.html.twig',[
            'vouchers' => $vouchers,
            'numberOfPages' => $nrOfPages,
            'currentPage' => $request->get('page'),
            'filterFrom' => $request->get('filterFrom'),
            'filterTo' => $request->get('filterTo'),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/voucher/all/reset-filters", name="voucher_all_reset_filters")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function resetFiltersAction()
    {
        return $this->redirectToRoute('voucher_all', ['page' => 1]);
    }

    /**
     * @Route("/voucher/{id}", name="voucher_details")
     * @ParamConverter("voucher", class="AppBundle:Voucher")
     * @Template("floathamburg/voucher_details.html.twig")
     * @param Voucher $voucher
     * @return array
     */
    public function voucherAction(Voucher $voucher)
    {
        return ['voucher' => $voucher];
    }

    /**
     * @Route("/voucher/use/{id}", name="voucher_use")
     *
     * @param Request $request
     * @param Voucher $voucher
     */
    public function useVoucherAction(Request $request, Voucher $voucher = null)
    {
        if ($voucher == null || $voucher->isBlocked()) {
            return $this->redirectToRoute('voucher_search');
        }

        $form = $this->createForm(VoucherUseType::class, null, ['voucherUsages' => $voucher->getUsages()]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $errors = '';
            if (count($form->getData()['used_for']) == 0) {
                $errors .= 'You must use the voucher for something! ';
            }
            if ($form->getData()['usage'] == 'PARTIAL_USE' &&
                ($form->getData()['partial_amount'] <= 0 ||
                $form->getData()['partial_amount'] > $voucher->getRemainingValue())
            ) {
                $errors .= 'Invalid partial amount value! ';
            }

            if ($errors !== '') {
                return $this->render('floathamburg/voucheruse.html.twig', [
                    'form' => null,
                    'submitted' => true,
                    'errors' => $errors,
                ]);
            }

            $em = $this->getDoctrine()->getManager();
            $this->savePaymentDetails($voucher, $em, $form->getData());
            $em->persist($voucher);
            $em->flush();

            return $this->render('floathamburg/voucheruse.html.twig', [
                'form' => null,
                'submitted' => true,
                'errors' => null,
            ]);
        }

        return $this->render('floathamburg/voucheruse.html.twig', [
            'form' => $form->createView(),
            'submitted' => false,
            'voucher' => $voucher
        ]);
    }

    /**
     * @param Voucher $voucher
     * @param ObjectManager $em
     * @param array $formData
     */
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
     * Fill voucher details
     *
     * @param Voucher $voucher
     */
    protected function fillVoucherDetails(Voucher $voucher)
    {
        $voucher->setShopWhereCreated($this->getUser()->getShop());
        $voucher->setAuthor($this->getUser());
    }

    /**
     * @Route("/voucher/block/{id}/{parentRoute}/{page}", name="voucher_block")
     *
     * @param Voucher $voucher
     * @param string  $parentRoute
     * @param string  $page
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function blockVoucherAction(Voucher $voucher, string $parentRoute = 'voucher_all', string $page = '1')
    {
        if ($voucher === null) {
            throw new \UnexpectedValueException("Cannot block voucher. Voucher is invalid!");
        }

        if ($parentRoute !== 'voucher_all' && $parentRoute !== 'voucher_search') {
            throw new \UnexpectedValueException("Cannot block voucher. Invalid parent route!");
        }

        $voucher->setBlocked(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($voucher);
        $em->flush();

        return $this->redirectToRoute($parentRoute, ['page' => $page]);
    }

    /**
     * @param string $voucherLetter
     * @param Voucher $voucher
     */
    protected function calculateVoucherCode(string $voucherLetter, Voucher $voucher)
    {
        $shopId = $this->getUser()->getShop()->getId();
        if ($voucher->isOnlineVoucher()) {
            $shopId = 0;
        }
        $voucherCodeInfo = $this->getDoctrine()->getRepository('AppBundle:VoucherCodeInformation')->find($shopId);
        $voucher->setVoucherCode('201'.$voucherLetter.$voucherCodeInfo->getNextVoucherCode());
        $em = $this->getDoctrine()->getManager();
        $em->persist($voucherCodeInfo);
        $em->flush();
    }
}
