<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function isDefined($array, $key)
    {
        if (isset($array[$key]) && trim($array[$key]) != '')
        {
            return true;
        }

        return false;
    }
}
