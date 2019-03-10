<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.10.18
 * Time: 23:44
 */

namespace App\Controllers\Api;

use App\Entities\User;
use App\Http\Request;
use App\Http\Response;
use App\Repositories\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Src\Facades\Bcrypt;
use Src\Facades\JWTToken;

class UserController extends ApiBaseController
{
    /**
     * @var EntityManager|mixed
     */
    private $manager;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UserController constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);

        $this->manager = container()->get(EntityManager::class);
        $this->userRepository = $this->manager->getRepository(User::class);

    }

    /**
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function index()
    {
        try {
            $users = $this->userRepository
                ->pagination($this->request->get('currentPage'), $this->request->get('perPage'))
                ->getResult();

            $total = $this->userRepository
                ->findTotal()
                ->getTotal();
        } catch (NoResultException  | NonUniqueResultException $e) {
            return $this->json(['success' => false, 'error' => ['No result founded']], 400);
        }


        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'id' => $user['id'],
                'name' => $user['first_name'] . ' ' . $user['last_name'],
                'email' => $user['email'],
                'created_at' => $user['created_at']->format('Y-m-d H:i:s'),
            ];
        }

        $result = [
            'items' => $data,
            'perPage' => (int)$this->request->get('perPage'),
            'currentPage' => (int)$this->request->get('currentPage'),
            'total' => (int)$total,
        ];

        return $this->json(['success' => true, 'data' => $result]);
    }

    /**
     * @param $id
     * @return \App\Http\Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function show($id)
    {
        $manager = container()->get(EntityManager::class);
        $qb = $manager->createQueryBuilder()->select(['u.id', 'u.name', 'u.email'])->from(User::class, 'u')->where('u.id = :id')->setParameter('id', $id);
        $user = $qb->getQuery()->getSingleResult();

        if (!empty($user)) {

            $data = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
            ];


            return $this->json(['success' => true, 'data' => $data]);
        }


        return $this->json(['success' => false], 404);
    }

    /**
     * @return \App\Http\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function store()
    {
        $manager = container()->get(EntityManager::class);
        $user = new User();

        if (($this->request->has('name') && $this->request->name != false)
        && ($this->request->has('email') && $this->request->get('email') != false)
        && ($this->request->has('password') && strlen($this->request->get('password')) > 7)) {

            $password = Bcrypt::hash($this->request->get('password'));

            $user->setName($this->request->get('name'));
            $user->setPassword($password);
            $user->setEmail($this->request->get('email'));

            $manager->persist($user);
            $manager->flush();

            return $this->json(['success' => true]);
        }


        return $this->json(['success' => false, 'error' => ['name & email & password' => 'required']], 400);
    }

    /**
     * @param $id
     * @return \App\Http\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function update($id)
    {
        $manager = container()->get(EntityManager::class);
        $user = $manager->getRepository(User::class)->find($id);


        if (empty($user)) {
            return $this->json(['success' => false, 'error' => ['Not Found']], 404);
        }

        if (!empty($this->request->get('name'))) {

            $user->setName($this->request->get('name'));
            $manager->persist($user);
            $manager->flush();

            return $this->json(['success' => true]);
        }

        return $this->json(['success' => false, 'error' => ['name' => 'required']], 404);

    }

    /**
     * @param $id
     * @return \App\Http\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function delete($id)
    {
        $manager = container()->get(EntityManager::class);
        $user = $manager->getRepository(User::class)->find($id);

        if (!empty($user)) {
            $manager->remove($user);
            $manager->flush();

            return $this->json(['success' => true]);


        }
        return $this->json(['success' => false], 404);
    }

    public function getUserByToken()
    {
        $token = $this->request->get('token');

        if (empty($token)) {
            return $this->json(['error' => ['token' => ['Токен отсутствует']]], 401);
        }

        $userId = JWTToken::getAuthId($token);

        $manager = container()->get(EntityManager::class);
        $qb = $manager->createQueryBuilder()->select(['u.id', 'u.first_name', 'u.last_name', 'u.email'])->from(User::class, 'u')->where('u.id = :id')->setParameter('id', $userId);
        $user = $qb->getQuery()->getSingleResult();

        if (empty($user)) {
            return $this->json(['error' => ['user' => ['Пользователь не найден']]], 401);
        }

        $data = [
            'id' => $user['id'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
        ];

        return $this->json(['data' => $data]);
    }
}