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
    private $request;
    /**
     * @param Request $request
     * @param \App\Http\Response $response
     * @param callable|null $next
     * @return Response|static
     * @throws \AuthExceptions
     */
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        $token = $this->getToken($request);

        if (empty($token)) {
            return $response->withStatus(401)
                ->withHeader('Content-Type', 'application/json')
                ->withStrToBody(json_encode(['error' => ['token' => 'Token required']], JSON_UNESCAPED_UNICODE));
        }


        if (!JWTToken::verify($token)) {
            return $response->withStatus(401)
                ->withHeader('Content-Type', 'application/json')
                ->withStrToBody(json_encode(['error' => ['token' => 'Token required']], JSON_UNESCAPED_UNICODE));
        }

        $id = JWTToken::getAuthId($token);

        try {
            $manager = container()->get(EntityManager::class);
            $user = $manager->getRepository(User::class)->find($id);
            Auth::setUser($user);
        } catch (AuthExceptions $e) {
            dd($e);
        }


        return $next($request, $response);
    }

    /**
     * @param $request
     * @return |null
     */
    private function getToken($request)
    {
        $header = $request->getHeader('Authorization');

        if (!empty($header)) {
            $explode = explode(' ', $header[0]);

            if (!empty($explode[1])) {
                return $explode[1];
            }
        }


        if ($request->has('token')) {
            return $request->get('token');
        }

        return null;
    }
}