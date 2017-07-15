<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\SearchVoucherType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
        $page = $this->validatePageNumber($request->get('page'));
        $offset = self::$NUMBER_OF_VOUCHERS_PER_PAGE*($page - 1);

        $vouchers = $this->getDoctrine()
            ->getRepository('AppBundle:Voucher')
            ->findAllFromPage($offset, self::$NUMBER_OF_VOUCHERS_PER_PAGE);

        return $this->render('floathamburg/vouchers.html.twig',[
            'vouchers' => $vouchers,
            'hasNextPage' => $this->validatePageNumber($page + 1) == 1 ? false : true,
            'hasPreviousPage' => ($this->validatePageNumber($page - 1) == 1 && $page != 2) ? false : true,
            'currentPage' => $page,
        ]);
    }

    /**
     * Validates the page number.
     *
     * @param int $page
     *
     * @return int  the page number if valid
     *              1 if it is not valid (the first page)
     */
    protected function validatePageNumber(int $page = null) : int
    {
        if ($page === null || $page < 1) {
            return 1;
        }

        //If the page number is to big compared to voucher database size
        //First page doesn't count
        if ($page > 1) {
            $voucherNumber = $this->getDoctrine()->getRepository('AppBundle:Voucher')->countAll();
            if (($page - 1) * self::$NUMBER_OF_VOUCHERS_PER_PAGE  > $voucherNumber ) {
                return 1;
            }
        }

        return $page;
    }
}
