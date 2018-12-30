<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.11.18
 * Time: 22:01
 */

namespace App\Repositories;

use App\Entities\Post;
use App\Entities\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use phpDocumentor\Reflection\Types\Integer;

/**
 * Class PostRepository
 * @package App\Repositories
 */
class PostRepository extends EntityRepository
{
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

