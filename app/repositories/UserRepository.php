<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18.11.18
 * Time: 22:30
 */

namespace App\Repositories;


use App\Entities\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class UserRepository extends EntityRepository
{
    /**
     * @var QueryBuilder
     */
    private $qb;

    /**
     * @param string $email
     * @return UserRepository
     */
    public function getByEmail(string $email): self
    {
        $this->qb = $this->_em->createQueryBuilder()
            ->select(['u.id', 'u.name', 'u.email', 'u.password'])
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
        return $this->qb->getQuery()
            ->getResult();
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function first()
    {

        return $this->qb->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }
}