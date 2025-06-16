<?php

declare(strict_types=1);

namespace KoKoP\Middleware;

// use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
// use Psr\Http\Message\ServerRequestInterface as Request;
// use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;

// use \KoKoP\Interfaces\AbstractServices;
// use \KoKoP\Interfaces\AbstractSession;


// class AuthMiddleware
// {
//     public function __invoke(Request $request, RequestHandler $handler): Response
//     {
//         $container = $request->getAttribute('container');

//         /** @var AbstractSession $session */
//         $session = $container->get('services')->getSessionService();

//         if (!$session->isValidSession()) {
//             // Redirect to login page if session is not valid
//             return $handler->handle(
//                 $request->withAttribute('action', 'HEPP')
//             );
//         }

//         return $handler->handle($request);
//     }
// }

return function (App $app) {
    $app->addBodyParsingMiddleware();
    // $app->addRoutingMiddleware();

    // Middleware to set the 'container' attribute
    // $app->add(function (Request $request, RequestHandler $handler) use ($app) {
    //     $request = $request->withAttribute('container', $app->getContainer());
    //     return $handler->handle($request);
    // });

    $app->addErrorMiddleware(true, true, true);
};
