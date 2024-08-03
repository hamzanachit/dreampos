<?php
// namespace Application\Middleware;

// use Laminas\Authentication\AuthenticationServiceInterface;
// use Laminas\Diactoros\Response\RedirectResponse;
// use Psr\Http\Message\ResponseInterface;
// use Psr\Http\Message\ServerRequestInterface;
// use Psr\Http\Server\MiddlewareInterface;
// use Psr\Http\Server\RequestHandlerInterface;

// class AuthenticationMiddleware implements MiddlewareInterface
// {
//     private $authService;

//     public function __construct(AuthenticationServiceInterface $authService)
//     {
//         $this->authService = $authService;
//     }

//     public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
//     {
//         if (!$this->authService->hasIdentity()) {
//             // Redirect to login page if not authenticated
//             return new RedirectResponse('/login');
//         }

//         // User is authenticated, proceed with the next middleware or controller action
//         return $handler->handle($request);
//     }
// }




namespace Application\Middleware;

use Laminas\Authentication\AuthenticationServiceInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Application\Service\DashboardService;

class AuthenticationMiddleware implements MiddlewareInterface
{
    private $authService;
    private $dashboardService;

    public function __construct(AuthenticationServiceInterface $authService, DashboardService $dashboardService)
    {
        $this->authService = $authService;
        $this->dashboardService = $dashboardService;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Check if the user is authenticated
        if (!$this->authService->hasIdentity()) {
            // Redirect to login page if not authenticated
            return new RedirectResponse('/login');
        }

        // User is authenticated, check for company association
        $user = $this->authService->getIdentity();
        $userid = $user['id'];

        $checkcompany = $this->dashboardService->getCheckCompanyById($userid);
        if ($checkcompany === null) {
            // Redirect to add company info page if no company is associated
            return new RedirectResponse('/settingActions/addcompanyinfo');
        }

        // User is authenticated and has an associated company, proceed with the next middleware or controller action
        return $handler->handle($request);
    }
}