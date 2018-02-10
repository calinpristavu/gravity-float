<?php

namespace AppBundle\Service;

use AppBundle\Repository\ShopRepository;
use AppBundle\Repository\UserRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class CsvWriter
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 */
class CsvWriter
{
    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var Connection
     */
    protected $conn;

    /**
     * @var ShopRepository
     */
    protected $shopRepository;

    /**
     * @var UserRepository
     */
    protected $userRepository;
    
    public function __construct(
        Connection $conn,
        TranslatorInterface $translator,
        ShopRepository $shopRepository,
        UserRepository $userRepository
    )
    {
        $this->translator = $translator;
        $this->conn = $conn;
        $this->shopRepository = $shopRepository;
        $this->userRepository = $userRepository;
    }

    public function getCsvVouchers(\DateTime $filterFrom = null, \DateTime $filterTo = null) : StreamedResponse
    {
        $response = new StreamedResponse();
        $response->setCallback(function () use ($filterFrom, $filterTo) {
            $handle = fopen('php://output', 'w+');
            $this->addVouchersHeader($handle);
            $this->addVouchersData($handle, $filterFrom, $filterTo);
            fclose($handle);
        });

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="Vouchers.csv"');
        return $response;
    }

    /**
     * @param resource $handle
     */
    private function addVouchersHeader($handle)
    {
        fputcsv(
            $handle,
            [
                "Voucher Code",
                "Creation Date",
                "Expiration Date",
                "Original Value",
                "Postal Charge",
                "Amount Used",
                "Amount Remaining",
                "Created by",
                "Created at",
                "Comment",
                "Blocked"
            ],
            ';'
        );
    }

    /**
     * @param resource $handle
     */
    private function addVouchersData($handle, \DateTime $filterFrom = null, \DateTime $filterTo = null)
    {
        $userData = $this->fetchVoucherData($filterFrom, $filterTo);
        while ($row = $userData->fetch()) {
            $createdAt = $this->shopRepository->find($row['shop_where_created_id'])->getName();
            $postalCharge = 0;
            if ($row['online_voucher']) {
                $createdAt .= ' Online';
                $postalCharge = $row['included_postal_charges'] == 1 ? 1.5 : 0;
            }
            fputcsv($handle, [
                $row['voucher_code'],
                $row['creation_date'],
                $row['expiration_date'],
                $row['remaining_value'] + $row['partial_payment'],
                $postalCharge . '€',
                $row['partial_payment'],
                $row['remaining_value'],
                $this->userRepository->find($row['author_id'])->getName(),
                $createdAt,
                $row['comment'],
                $row['blocked'] ? 'Yes' : 'No',
            ], ';');
        }
    }

    private function fetchVoucherData(\DateTime $filterFrom = null, \DateTime $filterTo = null) : Statement
    {
        $sql = "SELECT
            voucher_code,
            online_voucher,
            creation_date,
            expiration_date,
            remaining_value,
            included_postal_charges,
            partial_payment,
            author_id,
            shop_where_created_id,
            comment,
            blocked
        FROM vouchers 
        WHERE creation_date IS NOT NULL";

        $tag = ' WHERE';
        if ($filterFrom !== null) {
            $filterFrom = $filterFrom->format('Ymd');
            $sql .= "WHERE DATE(creation_date) >= '$filterFrom'";
            $tag = ' AND';
        }

        if ($filterTo !== null) {
            $filterTo = $filterTo->format('Ymd');
            $sql .= $tag . " DATE(creation_date) <= '$filterTo'";
        }

        return $this->conn->query($sql);
    }
}
