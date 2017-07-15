<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\SearchVoucherType;
use AppBundle\Form\Type\VoucherDateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
        $searchForm = $this->createForm(SearchVoucherType::class);

        $searchForm->handleRequest($request);
        if ($searchForm->isValid()) {
            $voucherCode = $searchForm->getData()['vouchercode'];
            $voucher = $this->getDoctrine()
                ->getRepository('AppBundle:Voucher')
                ->findOneBy(['voucherCode' => $voucherCode]);
            $shops = $this->getDoctrine()->getRepository('AppBundle:Shop')->findAll();

            return $this->render('floathamburg/vouchersearch.html.twig', [
                'searchForm' => $searchForm->createView(),
                'voucher' => $voucher,
                'submitted' => true,
                'shops' => $shops,
            ]);
        }

        return $this->render('floathamburg/vouchersearch.html.twig', [
            'searchForm' => $searchForm->createView(),
            'voucher' => null,
            'submitted' => false,
            'shops' => null,
        ]);
    }

    /**
     * @Route("/voucher/create", name="voucher_create")
     */
    public function createVoucherAction()
    {
        return $this->render('floathamburg/vouchercreate.html.twig');
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
        $form = $this->createForm(VoucherDateType::class);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $request->request->remove('created_at');
            $request->request->set('created_at', $form->getData()['created_at']);
        }

        if ($request->get('created_at') !== null) {
            return $this->filteredVouchers($request, $form);
        }

        return $this->allVouchers($request, $form);
    }

    protected function filteredVouchers(Request $request, Form $form)
    {
        $requestParam = $request->get('created_at');
        $year = $requestParam === 'CURRENT_YEAR' ? date("Y") : date("Y", strtotime("-1 year"));
        $filteredVouchersCount = $this->getDoctrine()
            ->getRepository('AppBundle:Voucher')
            ->countAllFiltered($year);

        $page = $this->validatePageNumber($request->get('page'), $filteredVouchersCount);
        $offset = self::$NUMBER_OF_VOUCHERS_PER_PAGE*($page - 1);
        $currentPageVouchers = $this->getDoctrine()
            ->getRepository('AppBundle:Voucher')
            ->findAllFilteredFromPage($offset, self::$NUMBER_OF_VOUCHERS_PER_PAGE, $year)
        ;
        $shops = $this->getDoctrine()->getRepository('AppBundle:Shop')->findAll();

        return $this->render('floathamburg/vouchers.html.twig',[
            'vouchers' => $currentPageVouchers,
            'shops' => $shops,
            'hasNextPage' => $this->validatePageNumber($page + 1, $filteredVouchersCount) == 1 ? false : true,
            'hasPreviousPage' => ($this->validatePageNumber($page - 1, $filteredVouchersCount) == 1 && $page != 2) ? false : true,
            'currentPage' => $page,
            'form' => $form->createView(),
        ]);
    }

    protected function allVouchers(Request $request, Form $form)
    {
        $allVouchersCount = $this->getDoctrine()->getRepository('AppBundle:Voucher')->countAll();

        $page = $this->validatePageNumber($request->get('page'), $allVouchersCount);
        $offset = self::$NUMBER_OF_VOUCHERS_PER_PAGE*($page - 1);
        $currentPageVouchers = $this->getDoctrine()
            ->getRepository('AppBundle:Voucher')
            ->findAllFromPage($offset, self::$NUMBER_OF_VOUCHERS_PER_PAGE);
        $shops = $this->getDoctrine()->getRepository('AppBundle:Shop')->findAll();
        return $this->render('floathamburg/vouchers.html.twig',[
            'vouchers' => $currentPageVouchers,
            'shops' => $shops,
            'hasNextPage' => $this->validatePageNumber($page + 1, $allVouchersCount) == 1 ? false : true,
            'hasPreviousPage' => ($this->validatePageNumber($page - 1, $allVouchersCount) == 1 && $page != 2) ? false : true,
            'currentPage' => $page,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Validates the page number.
     *
     * @param int $page
     * @param int $items
     *
     * @return int the page number if valid
     *              1 if it is not valid (the first page)
     */
    protected function validatePageNumber(int $page = null, int $items) : int
    {
        if ($page === null || $page < 1) {
            return 1;
        }

        //If the page number is to big compared to voucher database size
        //First page doesn't count
        if ($page > 1) {
            if (($page - 1) * self::$NUMBER_OF_VOUCHERS_PER_PAGE  > $items ) {
                return 1;
            }
        }

        return $page;
    }

    /**
     * @param array $formData
     *
     * @return array
     */
    protected function prepareFoundVouchers(array $formData) : array
    {
        $year = null;
        switch ($formData['created_at']) {
            case 'CURRENT_YEAR':
                $year = date("Y");
                break;
            case 'LAST_YEAR':
                $year = date("Y", strtotime("-1 year"));
                break;
            default:
                break;
        }

         return $this->getDoctrine()
            ->getRepository('AppBundle:Voucher')
            ->findFromYear($year);
    }
}
