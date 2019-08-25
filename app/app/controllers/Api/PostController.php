<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.11.18
 * Time: 21:03
 */

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Entities\Post;
use App\Entities\User;
use App\Http\Request;
use App\Http\Response;
use App\Repositories\PostRepository;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\RequestInterface;
use Rakit\Validation\Validator;
use Spiral\Debug\Dumper;
use Src\Exceptions\AuthExceptions;
use Src\Facades\Auth;

class PostController extends ApiBaseController
{
    /**
     * @var EntityManager|mixed
     */
    private $manager;

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * PostController constructor.
     * @param Request $request
     * @param Response $response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(Response $response)
    {
        parent::__construct($response);

        $this->manager = container()->get(EntityManager::class);
        $this->postRepository = $this->manager->getRepository(Post::class);

    }

    /**
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function index(RequestInterface $request)
    {
        $posts = $this->postRepository
            ->pagination($request->get('currentPage'), $request->get('perPage'))
            ->getResult();

        $total = $this->postRepository
            ->findTotal()
            ->getTotal();

        $posts = array_map(function ($item) {
            if (is_object($item['created_at'])) {
                $item['created_at'] = $item['created_at']->format('Y-m-d H:i:s');
                $item['title'] = utf8_encode($item['title']);
                $item['message'] = utf8_encode($item['message']);
            }

            return $item;
        }, $posts);

        $result = [
            'items' => $posts,
            'currentPage' => (int)$request->get('currentPage'),
            'perPage' => (int)$request->get('perPage'),
            'total' => (int)$total,
        ];

        return $this->json(['success' => true, 'data' => $result]);
    }

    public function show(RequestInterface $request, $id)
    {
        $manager = container()->get(EntityManager::class);
        $qb = $manager->createQueryBuilder()->select(['p.id', 'p.message', 'p.title', 'p.user_id', 'p.created_at'])->from(Post::class, 'p')->where('p.id = :id')->setParameter('id', $id);
        $post = $qb->getQuery()->getSingleResult();

        if (!empty($post)) {

            $data = [
                'id' => $post['id'],
                'title' => $post['title'],
                'message' => $post['message'],
                'user_id' => $post['user_id'],
                'created_at' => $post['created_at'],
            ];


            return $this->json(['success' => true, 'data' => $data]);
        }


        return $this->json(['success' => false], 404);
    }

    /**
     *
     */
    public function store(RequestInterface $request)
    {
        $validator = new Validator();
        $validation = $validator->make($request->all(), (new Post())->getRules());
        $validation->validate();

        if ($validation->fails()) {
            return $this->json(['success' => false, 'error' => $validation->errors()], 400);
        }

        $post = new Post();
        $manager = container()->get(EntityManager::class);
        $user = $manager->getRepository(User::class)->find($request->get('user_id'));

        if (empty($user)) {
            return $this->json(['success' => false, 'error' => ['user_id' => ['User not Found']]], 400);
        }
        $post->setMessage($request->get('message'));
        $post->setTitle($request->get('title'));
        $post->setUser($user);
        $this->manager->persist($post);
        $this->manager->flush();

        return $this->json(['success' => true]);
    }

    /**
     * @param $id
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(RequestInterface $request, int $id)
    {
				$dumper = new Dumper();
		$dumper->setRenderer(Dumper::ERROR_LOG, new \Spiral\Debug\Renderer\ConsoleRenderer());
		$dumper->dump('PC Request', Dumper::ERROR_LOG);
		$dumper->dump($request, Dumper::ERROR_LOG);
        $validator = new Validator();
        $validation = $validator->make($request->all(), (new Post())->getRules());
        $validation->validate();


        if ($validation->fails()) {
            return $this->json(['success' => false, 'error' => $validation->errors()->toArray()], 400);
        }

        $manager = container()->get(EntityManager::class);
        $post = $manager->getRepository(Post::class)->find((int)$id);


        if (empty($post)) {
            return $this->json(['success' => false, 'error' => ['Post not Found']], 400);
        }

        $user = $manager->getRepository(User::class)->find($request->get('user_id'));

        if (empty($user)) {
            return $this->json(['success' => false, 'error' => ['user_id' => ['User not Found']]], 400);
        }


        $post->setUser($user);
        $post->setMessage($request->get('message'));
        $post->setTitle($request->get('title'));
        $manager->persist($post);
        $manager->flush();

        return $this->json(['success' => true]);

    }

    public function delete($id)
    {
        $manager = container()->get(EntityManager::class);
        $post = $manager->getRepository(Post::class)->find($id);

        if (!empty($post)) {
            $post->setDeletedAt(new \DateTime());
            $manager->persist($post);
            $manager->flush();

            return $this->json(['success' => true]);


        }
        return $this->json(['success' => false], 404);
    }
}
