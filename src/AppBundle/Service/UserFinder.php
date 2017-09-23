<?php

namespace AppBundle\Service;

use AppBundle\Repository\UserRepository;
use Doctrine\ORM\QueryBuilder;

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
     * Class constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository, int $usersPerPage)
    {
        $this->userRepository = $userRepository;
        $this->usersPerPage = $usersPerPage;
    }

    /**
     * @param array $filters
     *
     * @return UserFinder
     */
    public function setFilters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @return array
     */
    public function getUsers()
    {
        $queryBuilder = $this->userRepository->createQueryBuilder('v');
        $this->applyFiltersToQueryBuilder($queryBuilder);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param QueryBuilder $queryBuilder
     */
    private function applyFiltersToQueryBuilder(QueryBuilder $queryBuilder)
    {
        if (isset($this->filters['page'])) {
            $page = $this->filters['page'];
            if (!is_int($this->filters['page']) || $page <= 0) {
                $page = 1;
            }

            $queryBuilder->setFirstResult(($page - 1)*$this->usersPerPage);
        }

        if (isset($this->filters['items_per_page'])) {
            $queryBuilder->setMaxResults($this->filters['items_per_page']);
        }
    }
}
