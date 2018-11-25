<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24.10.18
 * Time: 18:21
 */

namespace App\Controllers;

use App\Cache\PostCacheRepository;
use App\Http\Request;
use App\Http\Response;
use App\Http\Stream;
use Doctrine\ORM\EntityManager;
use App\Repositories\PostRepository;
use App\Entities\Post;
use function GuzzleHttp\Psr7\stream_for;


/**\
 * Class HomeController
 */
class HomeController extends BaseController
{
    /**
     * @var
     */
    private $manager;

    /**
     * @var
     */
    private $postRep;

    /**
     * @var PostCacheRepository
     */
    private $postCache;

    /**
     * HomeController constructor.
     * @param Request $request
     * @param Response $response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);

        $this->manager = container()->get(EntityManager::class);
        $this->postRep = $this->manager->getRepository(Post::class);

    }

    /**
     * @return Response
     */
    public function index()
    {
        $this->postCache = new PostCacheRepository();
        if (!$this->postCache->hasPosts()) {
            $posts = $this->postRep->getAllPosts()->getResult();
            $this->postCache->setPosts($posts);
        } else {
            $posts = $this->postCache->getPosts();
        }

        include_once __ROOT__ .'/resources/views/home.php';


        return $this->response();
    }
}