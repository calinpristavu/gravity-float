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
        return (int)$this->createQueryBuilder('v')
            ->select('count(v.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param string $year
     *
     * @return int
     */
    public function countAllFiltered(string $year)
    {
        return (int)$this->createQueryBuilder('v')
            ->select('count(v.id)')
            ->where('YEAR(v.creationDate) = :year')
            ->setParameter('year', $year)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param int $offset
     * @param int $numberOfResults
     * @param string $year
     *
     * @return array
     */
    public function findAllFilteredFromPage(int $offset, int $numberOfResults, string $year)
    {
        return $this->createQueryBuilder('v')
            ->where('YEAR(v.creationDate) = :year')
            ->setParameter('year', $year)
            ->setFirstResult($offset)
            ->setMaxResults($numberOfResults)
            ->getQuery()
            ->getResult();
    }
}