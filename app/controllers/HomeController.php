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
use Foolz\SphinxQL\Drivers\Pdo\Connection;
use Foolz\SphinxQL\SphinxQL;
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
     * @var PostRepository
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
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function index()
    {

//        include __ROOT__.'/sphinxapi.php';
//        $cl = new \SphinxClient();
//        $host = '172.23.0.6';
//        $port = '9312';
//
//        $cl->SetServer ( $host, $port );
//        $cl->SetConnectTimeout ( 3);
//        $cl->SetArrayResult ( true );
//        $cl->SetMatchMode ( SPH_MATCH_ALL );
//
////        $cl->SetSelect ( '' );
//
//
////$cl->SetRankingMode ( $ranker );
//        $res = $cl->Query ( 'SELECT * FROM test1 WHERE_MATCH("file1.mp3")', '*' );
//
//        echo '<pre>';
//        var_dump($res);
//        echo '</pre>';
//        die();

        $conn = new Connection();
        $conn->setParams(array('host' => 'sphinx', 'port' => 9306));

        $query = (new SphinxQL($conn))->select('*')
            ->from('test1');

        $result = $query->execute()->fetchAllAssoc();
        dd($result);
        $this->postCache = new PostCacheRepository();
        if (!$this->postCache->hasPosts()) {
            $posts = $this->postRep->getPostsWithUsers(10)->getResult();
            $this->postCache->setPosts($posts);
        } else {
            $posts = $this->postCache->getPosts();
        }

        include_once __ROOT__ .'/resources/views/home.php';


        return $this->response();
    }
}