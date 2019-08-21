<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24.10.18
 * Time: 18:21
 */

namespace App\Controllers;

use App\Cache\PostCacheRepository;
use App\Http\Response;
use App\Http\Stream;
use Doctrine\ORM\EntityManager;
use App\Repositories\PostRepository;
use App\Entities\Post;
use Foolz\SphinxQL\Drivers\Pdo\Connection;
use Foolz\SphinxQL\SphinxQL;
use Psr\Http\Message\RequestInterface as Request;
use Spiral\Debug\Dumper;
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

      $dumper = new Dumper();
      $dumper->setRenderer(Dumper::ERROR_LOG, new \Spiral\Debug\Renderer\ConsoleRenderer());
      $dumper->dump($this->request, Dumper::ERROR_LOG);
      $dumper->dump('Home Controller', Dumper::ERROR_LOG);
//        include __ROOT__.'/sphinxapi.php';
//        $cl = new \SphinxClient();
//        $host = 'sphinx';
//        $port = '9312';
//
//        $cl->SetServer ( $host, $port );
//        $cl->SetConnectTimeout ( 3);
//        $cl->SetArrayResult ( true );
//        $cl->SetRankingMode(SPH_RANK_PROXIMITY_BM25);
//        $cl->SetMatchMode(SPH_MATCH_EXTENDED);
//        $cl->SetSelect('*');
//        $res = $cl->Query ( '', 'posts' );
//
//        echo '<pre>';
//        var_dump($res);
//        echo '</pre>';
//        die();

//        $conn = new Connection();
//        $conn->setParams(array('host' => 'sphinx', 'port' => 9306));
//
//        $query = (new SphinxQL($conn))->select('*')
//            ->from('posts');
//
//        $result = $query->execute()->fetchAllAssoc();
//        dd($result);
        $this->postCache = new PostCacheRepository();
        if (!$this->postCache->hasPosts()) {
            $posts = $this->postRep->getPostsWithUsers(10)->getResult();
            $this->postCache->setPosts($posts);
        } else {
            $posts = $this->postCache->getPosts();
        }


        $body = file_get_contents(__ROOT__ . '/resources/views/home.php');


        return $this->response($body);
    }
}
