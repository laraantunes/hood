<?php
/**
 * 2019 Hood Framework
 */

namespace Controllers;

class ResourceTestController
{
    public function index($a, $b, $c, $route)
    {
        d($route);
        echo "teste";
    }
}