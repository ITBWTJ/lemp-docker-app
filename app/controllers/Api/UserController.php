<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.10.18
 * Time: 23:44
 */

namespace App\Controllers\Api;

use App\Entities\User;
use Doctrine\ORM\EntityManager;
use Src\Facades\Bcrypt;

class UserController extends ApiBaseController
{
    /**
     * @return \App\Http\Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function index()
    {
        $manager = container()->get(EntityManager::class);
        $users = $manager->getRepository(User::class)->findAll();

        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
            ];
        }


        return $this->json(['success' => true, 'data' => $data]);
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
}