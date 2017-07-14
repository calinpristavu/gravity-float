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
     * @param int $offset
     * @param int $numberOfResults
     *
     * @return array
     */
    public function findAllFromPage(int $offset, int $numberOfResults)
    {
        return $this->createQueryBuilder('v')
            ->setFirstResult($offset)
            ->setMaxResults($numberOfResults)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return int
     */
    public function countAll()
    {
        $result = $this->createQueryBuilder('v')
            ->select('count(v.id)')
            ->getQuery()
            ->getResult();

        return (int)$result[0][1];
    }
}