<?php
/**
 * 2019 Hood Framework
 */

namespace Hood\Target;

use Lead\Router\RouterException;
use Lead\Net\Http\Response;


/**
 * Class Arrow
 */
class Arrow
{
    /**
     * Aim to the Target Route
     */
    public static function aim()
    {
        $request = Route::request();
        $response = new Response();

        try {
            $route = Route::router()->route($request);
            echo $route->dispatch($response);

        } catch (RouterException $e) {
            if (getenv('environment')) {
                ops($e);
            } else {
                http_response_code($e->getCode());
            }
        }
    }
}