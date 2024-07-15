<?php
namespace Application\Middleware;

use Laminas\Authentication\AuthenticationServiceInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthenticationMiddleware implements MiddlewareInterface
{
    private $authService;

    public function __construct(AuthenticationServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$this->authService->hasIdentity()) {
            // Redirect to login page if not authenticated
            return new RedirectResponse('/login');
        }

        // User is authenticated, proceed with the next middleware or controller action
        return $handler->handle($request);
    }
}