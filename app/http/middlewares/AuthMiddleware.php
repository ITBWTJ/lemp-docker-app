<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.11.18
 * Time: 7:07
 */

namespace App\Http\Middlewares;

use App\Entities\User;
use App\Repositories\UserRepository;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Relay\MiddlewareInterface;
use Src\Exceptions\AuthExceptions;
use Src\Facades\Auth;
use Src\Facades\JWTToken;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @param Request $request
     * @param Response $response
     * @param callable|null $next
     * @return Response|static
     * @throws \AuthExceptions
     */
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        $header = $request->getHeader('Authorization');
        if (empty($header[0])) {
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        $explode = explode(' ', $header[0]);

        if (empty($explode[1])) {
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        if (!JWTToken::verify($explode[1])) {
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        $id = JWTToken::getAuthId($explode[1]);

        try {
            $manager = container()->get(EntityManager::class);
            $user = $manager->getRepository(User::class)->find($id);
            Auth::setUser($user);
        } catch (AuthExceptions $e) {
            dd($e);
        }


        return $next($request, $response);
    }
}