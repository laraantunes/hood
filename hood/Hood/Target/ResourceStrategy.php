<?php
/**
 * 2019 Hood Framework
 */

namespace Hood\Target;


use Lead\Inflector\Inflector;

class ResourceStrategy
{
    public function __invoke($router, $resource, $options = [])
    {
        $controllerName = $resource;
        if (substr($resource, strlen($resource) - 10, 10) == "Controller") {
            $resource = substr($resource, 0, strlen($resource) - 10);
        }
        $resource = self::_noControllersNamespace($resource);

        if (empty($options['resourceName'])) {
            $options['resourceName'] = Inflector::dasherize(Inflector::underscore($resource));
        }

        $controller = new $controllerName();

        $router->get($options['resourceName'], function($route) use ($controller) {
            return $controller->index($route->request, $route->params, $route->response, $route);
        });

        $router->get($options['resourceName'] . '/{id}', function($route) use ($controller) {
            return $controller->show($route->params['id'], $route->request, $route->params, $route->response, $route);
        });

        $router->get($options['resourceName'] . '/{id}/edit', function($route) use ($controller) {
            return $controller->edit($route->params['id'], $route->request, $route->params, $route->response, $route);
        });

        $router->post($options['resourceName'] . '/{id}/create', function($route) use ($controller) {
            return $controller->create($route->params['id'], $route->request, $route->params, $route->response, $route);
        });

        $router->post($options['resourceName'] . '/{id}/update', function($route) use ($controller) {
            return $controller->update($route->params['id'], $route->request, $route->params, $route->response, $route);
        });

        $router->post($options['resourceName'] . '/{id}/save', function($route) use ($controller) {
            return $controller->save($route->params['id'], $route->request, $route->params, $route->response, $route);
        });

        $router->delete($options['resourceName'] . '/{id}', function($route) use ($controller) {
            return $controller->delete($route->params['id'], $route->request, $route->params, $route->response);
        });
    }

    /**
     * Removes the "Controllers" namespace from Resource ClassName
     * @param string $class
     * @return string
     */
    protected function _noControllersNamespace(string $class): string
    {
        $classNamespace = explode("\\", $class);
        if ($classNamespace[0] == 'Controllers') {
            unset($classNamespace[0]);
        }
        return implode("\\", $classNamespace);
    }
}