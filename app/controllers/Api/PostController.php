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
use Rakit\Validation\Validator;
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
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);

        $this->manager = container()->get(EntityManager::class);
        $this->postRepository = $this->manager->getRepository(Post::class);

    }

    /**
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function index()
    {
        $posts = $this->postRepository
            ->pagination($this->request->get('currentPage'), $this->request->get('perPage'))
            ->getResult();

        $total = $this->postRepository
            ->findTotal()
            ->getTotal();

        $result = [
            'items' => $posts,
            'currentPage' => (int)$this->request->get('currentPage') ?? 1,
            'perPage' => (int)$this->request->get('perPage') ?? PostRepository::PER_PAGE,
            'total' => $total,
        ];


        return $this->json(['success' => true, 'data' => $result]);
    }

    public function show($id)
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
    public function store()
    {
        $validator = new Validator();
        $validation = $validator->make($this->request->all(), (new Post())->getRules());
        $validation->validate();

        if ($validation->fails()) {
            $this->json(['success' => false, 'error' => $validation->errors()], 400);
        }

        $post = new Post();
        $manager = container()->get(EntityManager::class);
        $user = $manager->getRepository(User::class)->find($this->request->get('user_id'));

        if (empty($user)) {
            return $this->json(['success' => false, 'error' => ['user_id' => ['User not Found']]], 400);
        }
        $post->setMessage($this->request->get('message'));
        $post->setTitle($this->request->get('title'));
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
    public function update($id)
    {
        $validator = new Validator();
        $validation = $validator->make($this->request->all(), (new Post())->getRules());
        $validation->validate();


        if ($validation->fails()) {
            return $this->json(['success' => false, 'error' => $validation->errors()->toArray()], 400);
        }

        $manager = container()->get(EntityManager::class);
        $post = $manager->getRepository(Post::class)->find($id);


        if (empty($post)) {
            return $this->json(['success' => false, 'error' => ['Post not Found']], 400);
        }

        $user = $manager->getRepository(User::class)->find($this->request->get('user_id'));

        if (empty($user)) {
            return $this->json(['success' => false, 'error' => ['user_id' => ['User not Found']]], 400);
        }


        $post->setUser($user);
        $post->setMessage($this->request->get('message'));
        $post->setTitle($this->request->get('title'));
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