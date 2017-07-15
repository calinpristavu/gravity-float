<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 */
class UserRepository extends EntityRepository
{
    /**
     * @param int $offset
     * @param int $numberOfResults
     *
     * @return array
     */
    public function findAllFromPage(int $offset, int $numberOfResults)
    {
        return $this->createQueryBuilder('u')
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
        $result = $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->getQuery()
            ->getResult();

        return (int)$result[0][1];
    }
}
