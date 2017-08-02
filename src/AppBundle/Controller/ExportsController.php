<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class ExportsController
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 */
class ExportsController extends Controller
{
    /**
     * @Route("/user/export-vouchers", name="user_export_vouchers")
     */
    public function exportVouchersAction(Request $request) : StreamedResponse
    {
        $filterFrom = $request->get('filterFrom') != null ? new \DateTime($request->get('filterFrom').' 00:00') : null;
        $filterTo = $request->get('filterTo') != null ? new \DateTime($request->get('filterTo'). ' 23:59') : null;
        return $this->get('csv.writer')->getCsvVouchers($filterFrom, $filterTo);
    }
}
