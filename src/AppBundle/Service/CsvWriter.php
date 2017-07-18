<?php

namespace AppBundle\Service;

use AppBundle\Repository\ShopRepository;
use AppBundle\Repository\UserRepository;
use Doctrine\DBAL\Connection;
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

    /**
     * CsvWriter constructor.
     *
     * @param Connection $conn
     * @param TranslatorInterface $translator
     * @param ShopRepository $shopRepository
     * @param UserRepository $userRepository
     */
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

    /**
     * @return StreamedResponse
     */
    public function getCsvVouchers()
    {
        $response = new StreamedResponse();
        $response->setCallback(function () {
            $handle = fopen('php://output', 'w+');
            $this->addVouchersHeader($handle);
            $this->addVouchersData($handle);
            fclose($handle);
        });

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="Vouchers.csv"');
        return $response;
    }

    /**
     * @param $handle
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
            ],
            ';'
        );
    }

    /**
     * @param $handle
     */
    private function addVouchersData($handle)
    {
        $userData = $this->fetchVoucherData();
        while ($row = $userData->fetch()) {
            fputcsv($handle, [
                $row['voucher_code'],
                $row['creation_date'],
                $row['expiration_date'],
                $row['remaining_value'] + $row['partial_payment'],
                $row['included_postal_charges'] == 1 ? 'Yes' : 'No',
                $row['partial_payment'],
                $row['remaining_value'],
                $this->userRepository->find($row['author_id'])->getName(),
                $this->shopRepository->find($row['shop_where_created_id'])->getName(),
            ], ';');
        }
    }

    /**
     * @return \Doctrine\DBAL\Driver\Statement
     */
    private function fetchVoucherData()
    {
        $sql = "SELECT
            voucher_code,
            creation_date,
            expiration_date,
            remaining_value,
            included_postal_charges,
            partial_payment,
            author_id,
            shop_where_created_id
        FROM vouchers";
        return $this->conn->query($sql);
    }
}
