<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Json;


class JsonController extends Controller
{
    public function create(): View
    {
        return view('json.create');
    }

    // execution time (for db && for route) only for this route
    // in case data needed for all routes preferable to use middleware

    public function store(Request $request)
    {

        if (!defined('LARAVEL_START')) {  // for test
            define('LARAVEL_START', microtime(true));
        }

        $request->validate([
            'data' => ['required', 'json'],
        ]);

        $start = microtime(true);
        $json = Json::create([
            'data' => $request->data,
            'user_id' => Auth::user()->id,
        ]);
        $dbtime = microtime(true) - $start;

        $time = microtime(true) - LARAVEL_START;

        $resp = array();
        $resp['message'] = 'Success!';
        $resp['id'] = $json->id;
        $resp['alltime'] = $time;
        $resp['dbtime'] = $dbtime;
        $resp['memory'] = memory_get_usage(true);

        return response(json_encode($resp), 200)
                   ->header('Content-Type', 'application/json');
    }
}
