<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 25.11.18
 * Time: 16:05
 */

namespace App\Cache;


use Predis\Client;

class PostCacheRepository
{
    /**
     * @var Client
     */
    private $cache;

    /**
     * PostCacheRepository constructor.
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct()
    {
        $this->cache = container()->get('redis');
    }

    /**
     * @return bool
     */
    public function hasPosts(): bool
    {
        return $this->cache->exists('posts');
    }

    /**
     * @return array|null
     */
    public function getPosts(): ?array
    {
        $data = $this->cache->get('posts');

        if (!empty($data)) {
            return unserialize($data);
        }

        return null;
    }

    /**
     * @param array $posts
     */
    public function setPosts(array $posts)
    {
        $this->cache->set('posts', serialize($posts));
    }

    public function deletePosts()
    {
        $this->cache;
    }

}