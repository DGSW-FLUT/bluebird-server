<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\Process\Process;

class WebhookController extends Controller
{
    public function deploy(Request $request)
    {

        $process = new Process('cd ' . base_path() . '; ./deploy.sh');
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }
}
