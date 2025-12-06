<?php
namespace Core\Routing;

use \Exception;

/**
 * Classe router per url più puliti
 */
class Router
{
    protected array $routes = [];

    /**
     * Funzione wrapper generica. Definisce una rotta.
     * @param method -> metodo [get, post].
     * @param url -> url.
     * @param handler -> collable -> funzione da eseguire.
     */
    public function add(string $method, string $url, $handler)
    {
        $this->routes[] = compact('method', 'url', 'handler');
    }

    public function get($url, $handler)
    {
        $this->add('GET', $url, $handler);
    }

    public function post($url, $handler)
    {
        $this->add('POST', $url, $handler);
    }

    /**
     * Risolve la richiesta 
     */
    public function resolve()
    {

        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            [$routeMethod, $url, $handler] = [$route['method'], $route['url'], $route['handler']];

            // Ci possono essere rotte con metodi diversi ma stesso url 
            if ($routeMethod !== $method) continue;

            $regex = "#^" . preg_replace('#\{[^}]+\}#', '([^/]+)', $url) . "$#";

            if (preg_match($regex, $path, $matches)) {
                array_shift($matches); // remove full match

                if (is_callable($handler)) {
                    return call_user_func_array($handler, $matches);
                }

                if (is_string($handler) && str_contains($handler, '@')) {
                    [$controller, $method] = explode('@', $handler);
                    $controller = "App\\Controller\\$controller";
                    $obj = new $controller;
                    return call_user_func_array([$obj, $method], $matches);
                }

                throw new Exception("Handler non valido");
            }
        }

        http_response_code(404);
        echo "404 - Not Found";
    }
}
