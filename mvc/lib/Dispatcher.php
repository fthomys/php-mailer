<?php


namespace PhpMailer;

use const Dom\NOT_FOUND_ERR;

class Dispatcher
{
    /** @var string Base url of the application */
    private const BASE_URL = '/';

    /**
     * Call the Controller and the corresponding method to show
     * the content of the requested address
     *
     * @return void
     */
    public static function dispatch(): void
    {
        $requestedUrl = explode('?', $_SERVER['REQUEST_URI'])[0];

        if (!str_starts_with($requestedUrl, static::BASE_URL)) {
            http_response_code(404);

            return;
        }

        if (str_ends_with($requestedUrl, '/')) {
            $requestedUrl = substr($requestedUrl, 0, -1);
        }

        $parts = explode(
            '/',
            trim(
                substr($requestedUrl, strlen(static::BASE_URL)),
                '/'
            )
        );

        $controllerName = $parts[0] === '' ? 'index' : $parts[0];
        $actionName = $parts[1] ?? 'index';

        $controllerClassPath = '\\PhpMailer\\Controller\\' . ucfirst($controllerName) . 'Controller';

        $actionMethod = strtolower($actionName) . 'Action';

        if (!class_exists($controllerClassPath) || !method_exists($controllerClassPath, $actionMethod)) {
            http_response_code(404);

            return;
        }

        (new $controllerClassPath())->$actionMethod();
    }

}
