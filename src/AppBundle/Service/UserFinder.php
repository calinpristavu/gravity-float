<?php

namespace AppBundle\Service;

use AppBundle\Repository\UserRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Class UserFinder
 * @author Ioan Ovidiu Enache <i.ovidiuenache@yahoo.com>
 */
class UserFinder
{
    public static $NUMBER_OF_USERS_PER_PAGE = 5;

    /**
     * @var array
     */
    private $filters;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var int
     */
    private $usersPerPage;

    /**
     * UserFinder constructor.
     */
    public function __construct(UserRepository $userRepository, int $usersPerPage)
    {
        $this->userRepository = $userRepository;
        $this->usersPerPage = $usersPerPage;
    }

    public function setFilters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    public function getUsers() : array
    {
        $queryBuilder = $this->userRepository->createQueryBuilder('v');
        $this->applyFiltersToQueryBuilder($queryBuilder);

        return $queryBuilder->getQuery()->getResult();
    }

    protected function applyFiltersToQueryBuilder(QueryBuilder $queryBuilder)
    {
        if (isset($this->filters['page'])) {
            if (!is_int($this->filters['page']) || $this->filters['page'] <= 0) {
                $page = 1;
            }

            $queryBuilder->setFirstResult(($page - 1)*$this->usersPerPage);
        }

        if (isset($this->filters['items_per_page'])) {
            $queryBuilder->setMaxResults($this->filters['items_per_page']);
        }
    }
}
