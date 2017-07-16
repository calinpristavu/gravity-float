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
        if (isset($this->filters['created_at'])) {
            switch ($this->filters['created_at']) {
                case 'CURRENT_YEAR':
                    $queryBuilder->andWhere($queryBuilder->expr()->eq(
                        'YEAR(v.creationDate)',
                        date("Y")
                    ));
                    break;
                case 'LAST_YEAR':
                    $queryBuilder->andWhere($queryBuilder->expr()->eq(
                        'YEAR(v.creationDate)',
                        date("Y", strtotime("-1 year"))
                    ));
                    break;
                default:
                    break;
            }
        }

        if (isset($this->filters['page'])) {
            $page = $this->filters['page'];
            if (!is_int($this->filters['page']) || $page <= 0) {
                $page = 1;
            }

            $queryBuilder->setFirstResult(($page - 1)*self::$NUMBER_OF_VOUCHERS_PER_PAGE);
        }

        if (isset($this->filters['items_per_page'])) {
            $queryBuilder->setMaxResults($this->filters['items_per_page']);
        }
    }
}
