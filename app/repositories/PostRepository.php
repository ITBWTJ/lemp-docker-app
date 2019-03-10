<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.11.18
 * Time: 22:01
 */

namespace App\Repositories;

use App\Entities\Post;
use App\Entities\traits\Pagination;
use App\Entities\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;


/**
 * Class PostRepository
 * @package App\Repositories
 */
class PostRepository extends EntityRepository
{
    use Pagination;
    /**
     * @var Query
     */
    private $q;


    /**
     * @return PostRepository
     */
    public function getAllPosts(): self
    {
        $this->q = $this->_em->createQueryBuilder()
            ->select(['p.id', 'p.title', 'p.message', 'p.user_id', 'p.created_at', 'p.deleted_at'])
            ->from(Post::class, 'p')
            ->where('p.deleted_at IS NULL')
            ->getQuery();

        return $this;
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
            ->select(['p.id', 'p.title', 'p.message', 'p.user_id', 'p.created_at', 'p.deleted_at'])
            ->from(Post::class, 'p')
            ->where('p.deleted_at IS NULL')
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
            ->select('count(p.id)')
            ->from(Post::class, 'p')
            ->where('p.deleted_at IS NULL')
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


    /**
     * @param array $post
     */
    public function create(array $post)
    {
        $this->q = $this->_em->createQueryBuilder()
            ->insert('posts')
            ->values([
                'title' => '?',
                'message' => '?',
                'user_id' => '?',
            ])
            ->setParameter(0, $post['title'])
            ->setParameter(0, $post['message'])
            ->setParameter(1, $post['user_id']);
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
     * @param int $limit
     * @return PostRepository
     */
    public function getPostsWithUsers(int $limit = 0): self
    {
        $builder = $this->_em->createQueryBuilder()
            ->select(['p.id', 'p.title', 'p.user_id', 'u.first_name', 'u.last_name', 'u.email'])
            ->from(Post::class, 'p')
            ->leftJoin(User::class, 'u', Query\Expr\Join::WITH, 'p.user_id = u.id');

        if ($limit) {
            $builder = $builder->setMaxResults($limit);
        }

        $this->q = $builder->getQuery();

        return $this;
    }

    /**
     * @return mixed
     */
//    public function getPostsWithUsers(): self
//    {
//        $this->q = $this->_em->createQuery('SELECT p.id, p.title, p.user_id, u.first_name, u.last_name, u.email FROM '. Post::class .' p JOIN  p.user u WHERE  p.deleted_at IS NULL');
//
//        return $this;
//    }


}

