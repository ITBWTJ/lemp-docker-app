<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17.11.18
 * Time: 17:41
 */

namespace App\Controllers\Auth;

use App\Controllers\Api\ApiBaseController;
use App\Controllers\BaseController;
use App\Entities\User;
use App\Http\Request;
use App\Http\Response;
use App\Repositories\UserRepository;
use Doctrine\ORM\EntityManager;
use Rakit\Validation\Validator;
use Src\Facades\Bcrypt;
use Src\Facades\JWTToken;

class LoginController extends BaseController
{


    /**
     * @return Response
     * @throws \ITBWTJohnnyJWT\Exceptions\NotSetConfigException
     * @throws \ITBWTJohnnyJWT\Exceptions\TokenException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function login()
    {
        $validator = new Validator();
        $validation = $validator->make($this->request->all(), ['email' => 'required|email', 'password' => 'required|min:8|max:60']);
        $validation->validate();

        if ($validation->fails()) {
            return $this->json(['success' => false, 'error' => $validation->errors()->all()], 400);
        }

        $manager = container()->get(EntityManager::class);
        $user = $manager->getRepository(User::class)->getByEmail($this->request->get('email'))->first();

        if (empty($user)) {
            return $this->json(['success' => false, 'error' => ["User by email". $this->request->get('email') ." not found"]], 400);
        }

        if (!Bcrypt::verify($this->request->get('password'), $user['password'])) {
            return $this->json(['success' => false, 'error' => ["Password wrong"]], 400);
        }


        $token = JWTToken::createToken($user);


        return $this->json(['success' => true, 'data' => ['token' => $token]], 200);
    }
}