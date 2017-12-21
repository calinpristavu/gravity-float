<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class VoucherRepository
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 */
class VoucherRepository extends EntityRepository
{
    public function countAll(\DateTime $from = null, \DateTime $to = null) : int
    {
        $qb = $this->createQueryBuilder('v');
        $qb
            ->select('count(v.id)')
            ->andWhere($qb->expr()->isNotNull('v.creationDate'))
            ->andWhere($qb->expr()->eq('v.enabled', true));

        if ($from !== null) {
            $qb
                ->andWhere('v.creationDate >= :from')
                ->setParameter('from', $from);
        }

        if ($to !== null) {
            $qb
                ->andWhere('v.creationDate <= :to')
                ->setParameter('to', $to);
        }

        return (int) $qb
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countAllWithCode(string $voucherCode = null) : int
    {
        if ($voucherCode === null) {
            return 0;
        }

        $qb = $this->createQueryBuilder('v');

        return (int) $qb
            ->select('count(v.id)')
            ->where($qb->expr()->orX(
                'v.voucherCode LIKE :code',
                'v.invoiceNumber LIKE :code',
                'v.orderNumber LIKE :code'
            ))
            ->andWhere($qb->expr()->eq('v.enabled', true))
            ->setParameter('code', '%' . $voucherCode . '%')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getAllWithCode(string $code = null) : array
    {
        if ($code === null) {
            return array();
        }

        $qb = $this->createQueryBuilder('v');

        return $qb
            ->where('v.voucherCode LIKE :code')
            ->andWhere($qb->expr()->eq('v.enabled', true))
            ->setParameter('code', '%' . $code . '%')
            ->getQuery()
            ->getResult();
    }
}
