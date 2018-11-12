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
            ];
        }


        return $this->json(['success' => true, 'data' => $data]);
    }

    /**
     * @param $id
     * @return \App\Http\Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function show($id)
    {
        $manager = container()->get(EntityManager::class);
        $user = $manager->getRepository(User::class)->find($id);

        if (!empty($user)) {

            $data = [
                'id' => $user->getId(),
                'name' => $user->getName(),
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

        if ($this->request->has('name') && $this->request->name != false) {
            $user->setName($this->request->get('name'));
            $manager->persist($user);
            $manager->flush();

            return $this->json(['success' => true]);
        }


        return $this->json(['success' => false, 'error' => ['name' => 'required']], 400);
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
        $jsonBody = $this->request->getBody();
        if (!empty($jsonBody) && !empty($user)) {
            $body = json_decode($jsonBody, 1);

            if (!empty($body['name'])) {
                $user->setName($body['name']);
                $manager->persist($user);
                $manager->flush();

                return $this->json(['success' => true]);
            }

            return $this->json(['success' => false, 'error' => ['name' => 'required']], 404);

        }
        return $this->json(['success' => false], 404);
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