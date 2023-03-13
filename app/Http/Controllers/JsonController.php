<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Json;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


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

    public function list(): View
    {
        $jsons = Json::paginate(10);
        return view('json.list', compact('jsons'));
    }
    

    public function read(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'exists:jsons,id'],
        ]);

        if ($validator->fails()) {
            return redirect()->route('json.list')
                        ->withErrors($validator)
                        ->withInput();
        }

        $json = Json::where('id', '=', $request->id)->first();
        return view('json.read', ['json'=>$json]);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'exists:jsons,id'],
        ]);
     
        $delete = Json::where('id', '=', $request->id)
                        ->where('user_id', '=', Auth::user()->id)
                        ->delete();
        $resp = array();
        $resp['message'] = $delete;
        return response(json_encode($resp), 200)
            ->header('Content-Type', 'application/json');
    }

    public function updateform(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'exists:jsons,id'],
        ]);
            
        if ($validator->fails()) {
            return redirect()->route('json.list')
                        ->withErrors($validator)
                        ->withInput();
        }
                        
        $json = Json::where('id', '=', $request->id)->first();
        return view('json.updateform', ['json'=>$json]);
    }


    public function update(Request $request)
    {
        $resp = array();

        $request->validate([
            'id' => ['required', 'exists:jsons,id'],
            // check if path exists - so structure of object gonna be save
            'data' => ['required', 'string',
                function ($attribute, $value, $fail) use ($request){
                    $paths = explode("\n", $value);
                    foreach($paths as $path){
                        $tmpPath = explode('=', $path);
                        $tempPath = explode("->", $tmpPath[0]);
                        array_shift($tempPath);                        
                        $pathExists = DB::table('jsons')
                            ->select(DB::raw("json_contains_path(data, 'one','$.".implode('.', $tempPath)."') jsonC"))
                            ->where('id', $request->id)
                            ->first();
                            if(!$pathExists->jsonC){
                                $fail('The path '.$path.' is invalid.');
                            }
                    }
                },
            ],
        ]);

        // or without fetching object from DB update with something like this:
        // DB::statement("UPDATE jsons SET data=JSON_SET(data, '$.".$hereThePath."', $hereTheValue) WHERE id=".$request->id);

        $row = Json::where([
                'id' => $request->id,
                'user_id' =>  Auth::user()->id
            ])->first();
        $data = json_decode($row->data);
        if(!$data){
            $resp['message'] = 'nothing to do';
            return response(json_encode($resp), 200)
                   ->header('Content-Type', 'application/json');
        }

//        foreach($qwes as $qwe){    //  quick && simple - but eval() eq evil (!?)
//            eval($qwe.';');
//        }

        $paths = explode("\n", $request->data);
        foreach($paths as $path){
            $tmpPath = explode('=', $path);
            $tempPath = explode("->", str_replace(array('[', "]"), array('->', ''), $tmpPath[0]));
            array_shift($tempPath);
            data_set($data, implode(".", $tempPath), $tmpPath[1]);  // native
        }

        $row->data = json_encode($data);
        $row->save();

        $resp['message'] = 'Success!';
        return response(json_encode($resp), 200)
                   ->header('Content-Type', 'application/json');
    }

}

