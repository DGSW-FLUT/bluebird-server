<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @OA\Info(
     *   title="Bluebird API",
     *   version="1.0",
     *   @OA\Contact(
     *     email="dgswflut@gmail.com",
     *     name="DGSW FLUT Gravatar email"
     *   )
     * )
     */
    protected function isDefined($array, $key)
    {
        if (isset($array[$key]) && trim($array[$key]) != '')
        {
            return true;
        }

        return false;
    }
}
