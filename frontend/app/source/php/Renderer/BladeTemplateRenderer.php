<?php

declare(strict_types=1);

namespace KoKoP\Renderer;

use \ComponentLibrary\Init as ComponentLibraryInit;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Flash\Messages;

use \KoKoP\Interfaces\AbstractServices;

final class BladeTemplateRenderer
{
    public function __construct(private AbstractServices $services, private Messages $flash) {}

    public function template(Response $response, string $template, array $data = []): Response
    {
        $session = $this->services->getSessionService();
        $user = $session->getUser();

        preg_match('/KoKoP\\\Action\\\(.*?)Action/', $template, $matches);
        $actionName = strtolower($matches[1]) ?? '';

        $blade = new ComponentLibraryInit(['viewPaths' => VIEWS_PATH])->getEngine();
        $bladeResult = $blade->makeView(
            'pages.' . $actionName,
            $data,
            [
                'flash' => $this->flash,
                'assets' => self::_getAssets() ?? false,
                'formattedUser' => $user ? $user->format() : null,
                'isAuthenticated' => $session->isValidSession(),
                'debugResponse' => $this->services->getConfigService()->getValue('DEBUG'),
            ]
        )->render();

        $response->getBody()->write(preg_replace('/(id|href)=""/', '', $bladeResult));

        return $response;
    }

    private static function _getAssets(): array
    {
        $manifestFile = BASE_PATH . 'assets/dist/manifest.json';

        if (!file_exists($manifestFile)) {
            return [];
        }

        return array_map(
            fn($file) => [
                'file' => $file,
                'type' => pathinfo($file, PATHINFO_EXTENSION),
                'id' => md5($file)
            ],
            json_decode(file_get_contents($manifestFile), true)
        );
    }
}
