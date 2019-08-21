<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.11.18
 * Time: 7:29
 */

namespace App\Controllers\Auth;


use App\Controllers\BaseController;
use App\Entities\User;
use Doctrine\ORM\EntityManager;
use ITBWTJohnnyJWT\Exceptions\ITBWTJohnnyJWTException;
use ITBWTJohnnyJWT\Exceptions\TokenException;
use Src\Facades\Bcrypt;
use Src\Facades\JWTToken;

class RegistrationController extends BaseController
{
    /**
     * @return \App\Http\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function register()
    {
        if (($this->request->has('name') && $this->request->name != false)
            && ($this->request->has('email') && $this->request->get('email') != false)
            && ($this->request->has('password') && strlen($this->request->get('password')) > 7)) {


            $manager = container()->get(EntityManager::class);
            $user = $manager->getRepository(User::class)->getByEmail($this->request->get('email'))->first();

            if (!empty($user)) {
                return $this->json(['success' => false, 'error' => ["User with email ".$this->request->get('email')." exists"]], 400);
            }

            $password = Bcrypt::hash($this->request->get('password'));

            $user = new User();
            $user->setName($this->request->get('name'));
            $user->setPassword($password);
            $user->setEmail($this->request->get('email'));

            $manager->persist($user);
            $manager->flush();


            try {
                $token = JWTToken::createToken((array)$user);
            } catch (ITBWTJohnnyJWTException $e) {
                return $this->json(['success' => false, 'error' => [$e->getMessage()]], 200);
            }

            return $this->json(['success' => true, 'data' => ['token' => $token]], 200);

        }

        return $this->json(['success' => false, 'error' => ['name & email & password' => 'required']], 400);
    }
}