<?php
namespace Core\Routing;

use Core\Helper\Logger;
use \Exception;

/**
 * Classe router per url più puliti
 */
class Router
{
    // Voglio accedere staticamente all'istanza (Pattern singleton)
    private static ?Router $instance = null;
    private array $routes = [];

    private function __construct() {}

    public static function getInstance() : Router {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Funzione wrapper generica. Definisce una rotta.
     * @param method -> metodo [get, post].
     * @param url -> url.
     * @param handler -> collable -> funzione da eseguire.
     */
    public function add(string $method, string $url, $handler) : void{
        $this->routes[] = compact('method', 'url', 'handler');
    }

    public function get($url, $handler) : void  {
        $this->add('GET', $url, $handler);
    }

    public function post($url, $handler): void {
        $this->add('POST', $url, $handler);
    }

    /**
     * Risolve la richiesta 
     */
    public function resolve() : mixed{

        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Rimuovi trailing slash se presente (tranne per la root)
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }

        foreach ($this->routes as $route) {
            [$routeMethod, $url, $handler] = [$route['method'], $route['url'], $route['handler']];

            // Ci possono essere rotte con metodi diversi ma stesso url 
            if ($routeMethod !== $method) continue;

            // Converti la rotta in regex
            // {id} diventa ([^/]+) - cattura tutto tranne /
            // {nome} diventa ([^/]+) ecc.
            $pattern = preg_replace('#\{[^\}]+\}#', '([^/]+)', $url);
            $regex = "#^" . $pattern . "$#";

            if (preg_match($regex, $path, $matches)) {
                array_shift($matches); // rimuovi il full match

                // Log solo se Logger è già inizializzato
                try {
                    Logger::getInstance()->info("Route matched", [
                        "method" => $method,
                        "path" => $path,
                        "route" => $url,
                        "params" => implode(',', $matches)
                    ]);
                } catch (\Exception $e) {
                    // Logger non ancora inizializzato, ignora
                }

                if (is_callable($handler)) {
                    return call_user_func_array($handler, $matches);
                }

                if (is_string($handler) && str_contains($handler, '@')) {
                    [$controller, $method] = explode('@', $handler);
                    $controllerClass = "App\\Controller\\$controller";
                    
                    if (!class_exists($controllerClass)) {
                        try {
                            Logger::getInstance()->error("Controller not found", ["controller" => $controllerClass]);
                        } catch (\Exception $e) {}
                        throw new Exception("Controller non trovato: $controllerClass");
                    }
                    
                    $obj = new $controllerClass;
                    
                    if (!method_exists($obj, $method)) {
                        try {
                            Logger::getInstance()->error("Method not found", [
                                "controller" => $controllerClass,
                                "method" => $method
                            ]);
                        } catch (\Exception $e) {}
                        throw new Exception("Metodo non trovato: $method in $controllerClass");
                    }
                    
                    return call_user_func_array([$obj, $method], $matches);
                }

                throw new Exception("Handler non valido");
            }
        }

        try {
            Logger::getInstance()->warning("404 - Route not found", [
                "method" => $method,
                "path" => $path
            ]);
        } catch (\Exception $e) {
            // Logger non inizializzato
        }

        http_response_code(404);
        echo "404 - Not Found: " . htmlspecialchars($path);
        return false;
    }

    /**
     * Restituisce tutte le rotte registrate (per debug)
     */
    public function getRoutes(): array {
      return $this->routes;
    }
}
