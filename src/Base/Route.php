<?php

class Route
{

    private static $routes = array();
    private static $pathNotFound = null;
    private static $methodNotAllowed = null;

    public static function register($expression, $function, $method = 'get')
    {
        array_push(self::$routes, array(
            'expression' => $expression,
            'function' => $function,
            'method' => $method
        ));
    }

    public static function pathNotFound($function)
    {
        self::$pathNotFound = $function;
    }

    public static function methodNotAllowed($function)
    {
        self::$methodNotAllowed = $function;
    }

    public static function run($basepath = '/', $case_matters = false, $trailing_slash_matters = false)
    {

        $parsed_url = parse_url($_SERVER['REQUEST_URI']);
        if (isset($parsed_url['path']) && $parsed_url['path'] != '/') {
            if ($trailing_slash_matters) {
                $path = $parsed_url['path'];
            } else {
                $path = rtrim($parsed_url['path'], '/');
            }
        } else {
            $path = '/';
        }
        $method = $_SERVER['REQUEST_METHOD'];
        $path_match_found = false;
        $route_match_found = false;
        foreach (self::$routes as $route) {
            if ($basepath != '' && $basepath != '/') {
                $route['expression'] = '(' . $basepath . ')' . $route['expression'];
            }
            $route['expression'] = '^' . $route['expression'];
            $route['expression'] = $route['expression'] . '$';

            // echo $route['expression'].'<br/>';
            if (preg_match('#' . $route['expression'] . '#' . ($case_matters ? '' : 'i'), $path, $matches)) {

                $path_match_found = true;
                foreach ((array) $route['method'] as $allowedMethod) {
                    if (strtolower($method) == strtolower($allowedMethod)) {
                        array_shift($matches); // Always remove first element. This contains the whole string
                        if ($basepath != '' && $basepath != '/') {
                            array_shift($matches); // Remove basepath
                        }
                        call_user_func_array($route['function'], $matches);
                        $route_match_found = true;
                        break;
                    }
                }
            }
        }

        if (!$route_match_found) {
            if ($path_match_found) {
                header("HTTP/1.0 405 Method Not Allowed");
                if (self::$methodNotAllowed) {
                    call_user_func_array(self::$methodNotAllowed, array($path, $method));
                }
            } else {
                header("HTTP/1.0 404 Not Found");
                if (self::$pathNotFound) {
                    call_user_func_array(self::$pathNotFound, array($path));
                }
            }
        }
    }
}
