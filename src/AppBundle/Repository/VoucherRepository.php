<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class VoucherRepository
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 */
class VoucherRepository extends EntityRepository
{
    /**
     * @return int
     */
    public function countAll()
    {
        return (int)$this->createQueryBuilder('v')
            ->select('count(v.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param string $voucherCode
     *
     * @return int
     */
    public function countAllWithCode(string $voucherCode = null)
    {
        if ($voucherCode === null) {
            return 0;
        }

        return (int)$this->createQueryBuilder('v')
            ->select('count(v.id)')
            ->where('v.voucherCode LIKE :code')
            ->setParameter('code', '%' . $voucherCode . '%')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param string $code
     *
     * @return array
     */
    public function getAllWithCode(string $code = null)
    {
        if ($code === null) {
            return array();
        }

        return $this->createQueryBuilder('v')
            ->where('v.voucherCode LIKE :code')
            ->setParameter('code', '%' . $code . '%')
            ->getQuery()
            ->getResult();
    }
}
