<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ExportsController
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 */
class ExportsController extends Controller
{
    /**
     * @Route("/user/export-vouchers", name="user_export_vouchers")
     */
    public function exportVouchersAction()
    {
        return $this->get('csv.writer')->getCsvVouchers();
    }
}
