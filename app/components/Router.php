<?php

namespace app\components;


class Router {
    const DEFAULT_ARGS_TYPE = ':all';

    protected static $ROUTS = [];
    protected static $ROUTE_ARGS_TYPES = [
        ':num' => 'integer',
        ':all' => 'all',
    ];


    protected $routsConfigPath;

    public function __construct() {
        $this->routsConfigPath = __DIR__ . '/../config/routes.php';
        $this->register();
    }

    /**
     * @param string $route - route value, example: 'className:method()'
     */
    public static function setRoute($route) {
        $routePathFull = key($route);
        $routePath = preg_replace('/(\[.*\])/', '', $routePathFull);
        $routePramsArgs = self::getRoutePathArgs($routePath, $routePathFull);

        $routeWay = array_shift($route);
        $routeClass = array_shift(explode(':', $routeWay));
        $routeMethod = end(explode(':', $routeWay));

        self::$ROUTS[$routePath] = [
            'class' => $routeClass,
            'method' => $routeMethod,
            'arguments' => $routePramsArgs
        ];

        return self;
    }

    protected static function getRoutePathArgs($routePath, $routePathFull) {
        preg_match('/\[(.*)\]/', $routePathFull, $matches);

        if (!$matches[1]) {
            return [];
        }

        $args = explode('/', $matches[1]);
        $args = array_values(array_filter($args));

        $argsValuesFromRoute = App::create()->uri->getSegmentsAfterRoute($routePath);
        $argsData = [];
        foreach ($args as $argPosition => $argType) {
            $argValue = $argsValuesFromRoute[$argPosition] ? $argsValuesFromRoute[$argPosition] : null;

            if ($argType === self::DEFAULT_ARGS_TYPE) {
                $argsData[] = $argValue;
                continue;
            }

            if (self::$ROUTE_ARGS_TYPES[$argType]) {
                settype($argValue, self::$ROUTE_ARGS_TYPES[$argType]);
                $argsData[] = $argValue;
            }
        }
        return $argsData;
    }

    protected function register() {
        include_once($this->routsConfigPath);
        return $this;
    }

    protected function exists($route) {
        foreach (self::$ROUTS as $routePath => $routeValue) {
            if (strstr($route, $routePath)) {
                return $routePath;
            }
        }
        return false;
    }

    protected function callRouteAction($route) {
        $routeData = self::$ROUTS[$route];
        $routeClassName = $routeData['class'];

        if (class_exists($routeClassName)) {
            $classObj = new $routeClassName();
            $classMethod = $routeData['method'];
            $methodArguments = $routeData['arguments'];

            if (method_exists($classObj, $classMethod)) {
                call_user_func_array(array($classObj, $classMethod), $methodArguments);
            } else {
                throw new \Exception("Route class '$routeClassName' method '$classMethod' not exist.");
            }
        } else {
            throw new \Exception("Route class '$routeClassName' not exist.");
        }
    }

    public function run() {
        $route = App::create()->uri->get();

        if ($routePath = $this->exists($route)) {
            $this->callRouteAction($routePath);
        } else {
            $this->error404();
        }

    }

    public function error404() {
        App::create()->template
            ->setData([
                'title' => '404 Not Found',
            ])
            ->render('errors/404');
    }


}