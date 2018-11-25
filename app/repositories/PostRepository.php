<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.11.18
 * Time: 22:01
 */

namespace App\Repositories;

use App\Entities\Post;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Class PostRepository
 * @package App\Repositories
 */
class PostRepository extends EntityRepository
{
    /**
     * @var QueryBuilder
     */
    private $qb;

    /**
     * @return PostRepository
     */
    public function getAllPosts(): self
    {
        $this->qb = $this->_em->createQueryBuilder()
            ->select(['p.id', 'p.message', 'p.user_id', 'p.created_at', 'p.deleted_at'])
            ->from(Post::class, 'p')
            ->where('p.deleted_at IS NULL');

        return $this;
    }

    /**
     * @param array $post
     */
    public function create(array $post)
    {
        $this->db = $this->_em->createQueryBuilder()
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
        return $this->qb->getQuery()
            ->getResult();
    }

}