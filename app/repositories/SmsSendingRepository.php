<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18.11.18
 * Time: 22:30
 */

namespace App\Repositories;


use App\Entities\SmsSending;
use App\Entities\traits\Pagination;
use App\Entities\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class SmsSendingRepository extends EntityRepository
{
    use Pagination;

    /**
     * @var QueryBuilder
     */
    private $q;


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
     * @return EmailSendingRepository
     */
    public function pagination(?int $currentPage, ?int $perPage): self
    {
        $this->perPage = $this->getPerPage($perPage);
        $maxResult = $this->getOffset($currentPage);

        $this->q = $this->_em->createQueryBuilder()
            ->select(['s_s.id', 's_s.name', 's_s.text', 's_s.phone',  's_s.created_at', 's_s.status'])
            ->from(SmsSending::class, 's_s')
            ->where('s_s.deleted_at IS NULL')
            ->setFirstResult($maxResult)
            ->setMaxResults($this->perPage)
            ->getQuery();

        return $this;
    }

    /**
     * @return EmailSendingRepository
     */
    public function findTotal(): self
    {
        $this->q = $this->_em->createQueryBuilder()
            ->select('count(s_s.id)')
            ->from(SmsSending::class, 's_s')
            ->where('s_s.deleted_at IS NULL')
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
