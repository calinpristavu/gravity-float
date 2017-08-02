<?php

namespace AppBundle\Service;

use AppBundle\Repository\VoucherRepository;
use Doctrine\ORM\QueryBuilder;

class VoucherFinder
{
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
     * @var int
     */
    private $vouchersPerPage;

    /**
     * Class constructor.
     *
     * @param VoucherRepository $voucherRepository
     */
    public function __construct(VoucherRepository $voucherRepository, int $vouchersPerPage)
    {
        $this->voucherRepository = $voucherRepository;
        $this->vouchersPerPage = $vouchersPerPage;
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

    private function applyFiltersToQueryBuilder(QueryBuilder $queryBuilder)
    {
        if (isset($this->filters['filterFrom'])) {
            $queryBuilder
                ->andWhere('v.creationDate >= :from')
                ->setParameter('from', $this->filters['filterFrom'] )
            ;
        }
        if (isset($this->filters['filterTo'])) {
            $queryBuilder
                ->andWhere('v.creationDate <= :to')
                ->setParameter('to', $this->filters['filterTo'])
            ;
        }

        if (isset($this->filters['page'])) {
            $page = $this->filters['page'];
            if (!is_int($this->filters['page']) || $page <= 0) {
                $page = 1;
            }

            $queryBuilder->setFirstResult(($page - 1)*$this->vouchersPerPage);
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
