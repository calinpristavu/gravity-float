<?php

namespace AppBundle\Service;

use AppBundle\Repository\VoucherRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Class VoucherFinder
 * @author Ioan Ovidiu Enache <i.ovidiuenache@yahoo.com>
 */
class VoucherFinder
{
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

    public function __construct(VoucherRepository $voucherRepository, int $vouchersPerPage)
    {
        $this->voucherRepository = $voucherRepository;
        $this->vouchersPerPage = $vouchersPerPage;
    }

    public function setFilters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    public function getVouchers() : array
    {
        $queryBuilder = $this->voucherRepository->createQueryBuilder('v');
        $this->applyFiltersToQueryBuilder($queryBuilder);

        return $queryBuilder->getQuery()->getResult();
    }

    protected function applyFiltersToQueryBuilder(QueryBuilder $queryBuilder)
    {
        $queryBuilder->andWhere($queryBuilder->expr()->eq('v.enabled', true));
        if (isset($this->filters['decreasing'])) {
            $queryBuilder->addOrderBy('v.creationDate', 'DESC');
        }

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
            $queryBuilder
                ->andWhere($queryBuilder->expr()->orX(
                    'v.voucherCode LIKE :code',
                    'v.invoiceNumber LIKE :code',
                    'v.orderNumber LIKE :code'
                ))
                ->setParameter("code", sprintf(
                    '%%%s%%',
                    $this->filters['voucherCode']
                ));
        }

        if (isset($this->filters['items_per_page'])) {
            $queryBuilder->setMaxResults($this->filters['items_per_page']);
        }
    }
}
