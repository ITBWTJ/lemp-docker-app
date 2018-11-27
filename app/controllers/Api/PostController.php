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
     * @return mixed
     */
    public function index()
    {
        $posts = $this->postRepository->getAllPosts()->getResult();


        return $this->json(['success' => true, 'data' => $posts]);
    }

    public function show($id)
    {
        $manager = container()->get(EntityManager::class);
        $qb = $manager->createQueryBuilder()->select(['p.id', 'p.message', 'p.user_id', 'p.created_at'])->from(Post::class, 'p')->where('p.id = :id')->setParameter('id', $id);
        $post = $qb->getQuery()->getSingleResult();

        if (!empty($post)) {

            $data = [
                'id' => $post['id'],
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
        $user = Auth::getUser();
        $post->setMessage($this->request->get('message'));
        $post->setUser($user);
        $this->manager->persist($post);
        $this->manager->flush();

        return $this->json(['success' => true]);
    }

    public function update($id)
    {
        $validator = new Validator();
        $validation = $validator->make($this->request->all(), (new Post())->getRules());
        $validation->validate();

        if ($validation->fails()) {
            $this->json(['success' => false, 'error' => $validation->errors()], 400);
        }

        $manager = container()->get(EntityManager::class);
        $post = $manager->getRepository(Post::class)->find($id);


        if (empty($post)) {
            return $this->json(['success' => false, 'error' => ['Not Found']], 404);
        }


        $post->setMessage($this->request->get('message'));
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