<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Payment;
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
use Symfony\Component\HttpFoundation\Request;

/**
 * Class VoucherController
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 */
class VoucherController extends Controller
{
    public static $NUMBER_OF_VOUCHERS_PER_PAGE = 5;

    /**
     * @Route("/voucher/", name="voucher_homepage")
     */
    public function homepageAction()
    {
        return $this->render('floathamburg/homepage.html.twig');
    }

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
            if ($voucher->isIncludedPostalCharges()) {
                $voucher->setRemainingValue($voucher->getRemainingValue() - 1.5);
            }
            $voucher->setVoucherCode(
                $this->get('voucher.code.generator')->generateCodeForVoucher($voucher)
            );
            $this->get('session')->set('voucher', $voucher);
            $usages = $voucher->getUsages();
            foreach ($usages as $key=>$usage) {
                if ($usage === 'massage') {
                    $usages[$key] .= ' '.$form['massage_type']->getData().' '.$form['time_for_massage']->getData();
                } else if ($usage === 'floating') {
                    $usages[$key] .= ' '.$form['time_for_floating']->getData();
                }
            }
            $voucher->setUsages($usages);

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
            $request->request->set('searchField', serialize($form->getData()['created_at']));
        }

        $filters = [
            'page' => (int)$request->get('page'),
            'items_per_page' => self::$NUMBER_OF_VOUCHERS_PER_PAGE,
        ];

        $voucherFinder = $this->get('voucher.finder');
        if ($request->get('searchField') !== null) {
            $filters['created_at'] = unserialize($request->get('searchField'));
        }

        $vouchers = $voucherFinder->setFilters($filters)->getVouchers();
        $allVouchersCount = $voucher = $this->getDoctrine()->getRepository('AppBundle:Voucher')->countAll();
        $nrOfPages = (int)($allVouchersCount / self::$NUMBER_OF_VOUCHERS_PER_PAGE) + 1;
        if ($allVouchersCount % self::$NUMBER_OF_VOUCHERS_PER_PAGE == 0) {
            $nrOfPages = $allVouchersCount / self::$NUMBER_OF_VOUCHERS_PER_PAGE;
        }

        return $this->render('floathamburg/vouchers.html.twig',[
            'vouchers' => $vouchers,
            'numberOfPages' => $nrOfPages,
            'currentPage' => $request->get('page'),
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
        if ($voucher == null) {
            return $this->redirectToRoute('voucher_homepage');
        }

        $form = $this->createForm(VoucherUseType::class);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->savePaymentDetails($voucher, $em, $form->getData());
            $em->persist($voucher);
            $em->flush();

            return $this->render('floathamburg/voucheruse.html.twig', [
                'form' => null,
                'submitted' => true,
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
}
