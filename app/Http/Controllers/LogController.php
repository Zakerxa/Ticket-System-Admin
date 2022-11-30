<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function index()
    {
        $log = Log::OrderBy('id', 'desc')->get();
        if($log) return response()->json(['response' => 'success','log' => $log]);
        else return response()->json(['response' => 'error','log' => $log]);
    }
    public function destory()
    {
        $delete = DB::table('logs')->delete();
        if ($delete) return response()->json(['response' => 'success','log' => $delete]);
        else return response()->json(['response' => 'error','log' => $delete]);
    }
}
