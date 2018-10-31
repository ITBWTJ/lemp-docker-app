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

        return $this->json($data);
    }

    public function store()
    {
        $manager = container()->get(EntityManager::class);
        $user = new User();
        $jsonBody = $this->request->getBody();
        if (!empty($jsonBody)) {
            $body = json_decode($jsonBody, 1);


            if (!empty($body['name'])) {
                $user->setName($body['name']);
                $manager->persist($user);
                $manager->flush();

                return $this->json(['success' => true]);
            }

        }
        return $this->json(['success' => false]);
    }
}