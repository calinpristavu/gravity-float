<?php

namespace AppBundle\Service;

use AppBundle\Repository\VoucherRepository;
use Doctrine\ORM\QueryBuilder;

class VoucherFinder
{
    public static $NUMBER_OF_VOUCHERS_PER_PAGE = 5;

    /**
     * @var int
     */
    private $page;

    /**
     * @var array
     */
    private $filters;

    /**
     * @var VoucherRepository
     */
    private $voucherRepository;

    /**
     * Class constructor.
     *
     * @param VoucherRepository $voucherRepository
     */
    public function __construct(VoucherRepository $voucherRepository)
    {
        $this->voucherRepository = $voucherRepository;
    }

    /**
     * @param int $page
     *
     * @return VoucherFinder
     */
    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @param array $filters
     *
     * @return VoucherFinder
     */
    public function setFilters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @return array
     */
    public function getVouchers()
    {
        $queryBuilder = $this->voucherRepository->createQueryBuilder('v');
        $this->applyFiltersToQueryBuilder($queryBuilder);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param QueryBuilder $queryBuilder
     */
    private function applyFiltersToQueryBuilder(QueryBuilder $queryBuilder)
    {
        if (isset($this->filters['filterFrom']) && isset($this->filters['filterTo'])) {
            $from = new \DateTime($this->filters['filterFrom']." 00:00:00");
            $to   = new \DateTime($this->filters['filterTo']." 23:59:59");

            $queryBuilder
                ->andWhere('v.creationDate BETWEEN :from AND :to')
                ->setParameter('from', $from )
                ->setParameter('to', $to)
            ;
        }

        if (isset($this->filters['page'])) {
            $page = $this->filters['page'];
            if (!is_int($this->filters['page']) || $page <= 0) {
                $page = 1;
            }

            $queryBuilder->setFirstResult(($page - 1)*self::$NUMBER_OF_VOUCHERS_PER_PAGE);
        }

        if (isset($this->filters['voucherCode'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->like(
                'v.voucherCode',
                $queryBuilder->expr()->literal('%' . $this->filters['voucherCode'] . '%')
            ));
        }

        if (isset($this->filters['items_per_page'])) {
            $queryBuilder->setMaxResults($this->filters['items_per_page']);
        }
    }
}
