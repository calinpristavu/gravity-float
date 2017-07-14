<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class VoucherController
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 */
class VoucherController extends Controller
{
    /**
     * @Route("/voucher/", name="voucher_homepage")
     */
    public function homepageAction()
    {
        return $this->render('floathamburg/vouchersearch.html.twig');
    }

    /**
     * @Route("/voucher/search", name="voucher_search")
     */
    public function searchVoucherAction()
    {
        return $this->render('floathamburg/vouchersearch.html.twig');
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
     */
    public function vouchersAction()
    {
        return $this->render('floathamburg/vouchers.html.twig');
    }
}
