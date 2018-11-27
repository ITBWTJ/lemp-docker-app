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
            ->select(['p.id', 'p.message', 'p.user_id', 'p.created_at', 'p.deleted_at'])
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
                'message' => '?',
                'user_id' => '?',
            ])
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
     * @return mixed
     */
    public function getPostsWithUsers(): self
    {
        $this->q = $this->_em->createQuery('SELECT p.id, p.message, p.user_id, u.name, u.email FROM '. Post::class .' p JOIN  p.user u WHERE  p.deleted_at IS NULL');

        return $this;
    }

}