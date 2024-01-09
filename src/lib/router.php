<?php
namespace lib;
class Router
{
    private $getRoutes = [];
    private $postRoutes = [];
    private $putRoutes = [];
    private $patchRoutes = [];
    private $deleteRoutes = [];

    public function get($route, $path_to_include)
    {
        $this->getRoutes[$route] = $path_to_include;
    }

    public function post($route, $path_to_include)
    {
        $this->postRoutes[$route] = $path_to_include;
    }

    public function put($route, $path_to_include)
    {
        $this->putRoutes[$route] = $path_to_include;
    }

    public function patch($route, $path_to_include)
    {
        $this->patchRoutes[$route] = $path_to_include;
    }

    public function delete($route, $path_to_include)
    {
        $this->deleteRoutes[$route] = $path_to_include;
    }

    public function any($route, $path_to_include)
    {
        $this->get($route, $path_to_include);
        $this->post($route, $path_to_include);
        $this->put($route, $path_to_include);
        $this->patch($route, $path_to_include);
        $this->delete($route, $path_to_include);
    }

    private function route($route, $path_to_include)
    {
        $callback = $path_to_include;
        if (!is_callable($callback)) {
            if (!strpos($path_to_include, '.php')) {
                $path_to_include .= '.php';
            }
        }
        if ($route == "/404") {
            call_user_func_array($callback, []);
            exit();
        }
        $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $request_url = rtrim($request_url, '/');
        $request_url = strtok($request_url, '?');

        $route_parts = explode('/', $route);
        $request_url_parts = explode('/', $request_url);
        array_shift($route_parts);
        array_shift($request_url_parts);

        if ($route_parts[0] == '' && count($request_url_parts) == 0) {
            if (is_callable($callback)) {
                call_user_func_array($callback, []);
                exit();
            }
            include_once __DIR__ . "/$path_to_include";
            exit();
        }
        if (count($route_parts) != count($request_url_parts)) {
            return;
        }

        $parameters = [];
        for ($__i__ = 0; $__i__ < count($route_parts); $__i__++) {
            $route_part = $route_parts[$__i__];
            if (preg_match("/^[$]/", $route_part)) {
                $route_part = ltrim($route_part, '$');
                array_push($parameters, $request_url_parts[$__i__]);
                $$route_part = $request_url_parts[$__i__];
            } else if ($route_parts[$__i__] != $request_url_parts[$__i__]) {
                return;
            }
        }

        if (is_callable($callback)) {
            call_user_func_array($callback, $parameters);
            exit();
        }
        include_once __DIR__ . "/$path_to_include";
        exit();
    }

    public function resolve()
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        switch ($method) {
            case 'get':
                $this->handleRoute($this->getRoutes);
                break;
            case 'post':
                $this->handleRoute($this->postRoutes);
                break;
            // Similar for PUT, PATCH, DELETE
            default:
                //

                // Handle unknown method or route not found
                $this->routeNotFound();
                break;
        }
    }

    private function handleRoute($routes)
    {
        $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $request_url = rtrim($request_url, '/');
        $request_url = strtok($request_url, '?');

        foreach ($routes as $route => $path) {
            $route_parts = explode('/', $route);
            $request_url_parts = explode('/', $request_url);
            array_shift($route_parts);
            array_shift($request_url_parts);

            if ($this->routeMatch($route_parts, $request_url_parts)) {
                $this->route($route, $path);
                return;
            }
        }

        $this->routeNotFound();
    }
    private function routeMatch($route_parts, $request_url_parts)
    {
        if (count($route_parts) != count($request_url_parts)) {
            return false;
        }

        for ($i = 0; $i < count($route_parts); $i++) {
            if (preg_match("/^[$]/", $route_parts[$i])) {
                continue; // This is a dynamic part of the route, skip comparison
            }

            if ($route_parts[$i] != $request_url_parts[$i]) {
                return false; // Static parts of the route do not match
            }
        }

        return true; // All static parts match, and dynamic parts are ignored
    }

    private function routeNotFound()
    {
        // Handle 404 Not Found
        if (isset($this->getRoutes['/404'])) {
            $this->route('/404', $this->getRoutes['/404']);
        } else {
            echo '404 Not Found';
            exit();
        }
    }

    public function out($text)
    {
        echo htmlspecialchars($text);
    }

    public function setCsrf()
    {
        session_start();
        if (!isset($_SESSION["csrf"])) {
            $_SESSION["csrf"] = bin2hex(random_bytes(50));
        }
        echo '<input type="hidden" name="csrf" value="' . $_SESSION["csrf"] . '">';
    }

    public function isCsrfValid()
    {
        session_start();
        if (!isset($_SESSION['csrf']) || !isset($_POST['csrf'])) {
            return false;
        }
        return $_SESSION['csrf'] == $_POST['csrf'];
    }
}