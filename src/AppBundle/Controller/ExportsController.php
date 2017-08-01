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
     * @Route("/user/export-vouchers/{filterFrom}/{filterTo}", name="user_export_vouchers")
     *
     * @param string $filterFrom
     * @param string $filterTo
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportVouchersAction(\DateTime $filterFrom = null, \DateTime $filterTo = null)
    {
        return $this->get('csv.writer')->getCsvVouchers($filterFrom, $filterTo);
    }
}
