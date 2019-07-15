<?php

namespace App\Http\Controllers;

class TestController extends Controller
{
    public function ping()
    {
        return response('Pong!');
    }
}
