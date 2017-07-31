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
     * @param null $from
     * @param null $to
     *
     * @return int
     */
    public function countAll($from = null, $to = null)
    {
        if ($from != null && $to != null) {
            $from = new \DateTime($from." 00:00:00");
            $to   = new \DateTime($to." 23:59:59");
            return (int)$this->createQueryBuilder('v')
                ->select('count(v.id)')
                ->where('v.creationDate BETWEEN :from AND :to')
                ->setParameter('from', $from)
                ->setParameter('to', $to)
                ->getQuery()
                ->getSingleScalarResult();
        }

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
