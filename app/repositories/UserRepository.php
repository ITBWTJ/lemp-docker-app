<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18.11.18
 * Time: 22:30
 */

namespace App\Repositories;


use App\Entities\traits\Pagination;
use App\Entities\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class UserRepository extends EntityRepository
{
    use Pagination;

    /**
     * @var QueryBuilder
     */
    private $q;

    /**
     * @param string $email
     * @return UserRepository
     */
    public function getByEmail(string $email): self
    {
        $this->q = $this->_em->createQueryBuilder()
            ->select(['u.id', 'u.first_name', 'u.last_name', 'u.email', 'u.password'])
            ->from(User::class, 'u')
            ->where('u.email = :email')
            ->setParameter('email', $email);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->q
            ->getResult();
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function first()
    {

        return $this->q->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    /**
     * @param int $currentPage
     * @param int $perPage
     * @return PostRepository
     */
    public function pagination(?int $currentPage, ?int $perPage): self
    {
        $this->perPage = $this->getPerPage($perPage);
        $maxResult = $this->getOffset($currentPage);

        $this->q = $this->_em->createQueryBuilder()
            ->select(['u.id', 'u.first_name', 'u.last_name', 'u.email',  'u.created_at'])
            ->from(User::class, 'u')
            ->where('u.deleted_at IS NULL')
            ->setFirstResult($maxResult)
            ->setMaxResults($this->perPage)
            ->getQuery();

        return $this;
    }

    /**
     * @return PostRepository
     */
    public function findTotal(): self
    {
        $this->q = $this->_em->createQueryBuilder()
            ->select('count(u.id)')
            ->from(User::class, 'u')
            ->where('u.deleted_at IS NULL')
            ->getQuery();

        return $this;
    }

    /**
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getTotal(): int
    {
        return $this->q->getSingleResult()[1];
    }
}