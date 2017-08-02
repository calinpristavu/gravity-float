<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ExportsController
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 */
class ExportsController extends Controller
{
    /**
     * @Route("/user/export-vouchers", name="user_export_vouchers")
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportVouchersAction(Request $request)
    {
        $filterFrom = $request->get('filterFrom') != null ? new \DateTime($request->get('filterFrom')) : null;
        $filterTo = $request->get('filterTo') != null ? new \DateTime($request->get('filterTo')) : null;
        return $this->get('csv.writer')->getCsvVouchers($filterFrom, $filterTo);
    }
}
