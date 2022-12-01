<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{

    public function index(Request $request)
    {
        if($request->user()->role_id == 2){
            $form = Ticket::OrderBy('updated_at', 'desc')->where('status',0)->filter(request(['search']))->paginate($request->per_page ?? 5);
            $noti = Ticket::where('status',0)->OrderBy('id','desc')->count();
        }

        if($request->user()->role_id == 3){
            $form = Ticket::OrderBy('id', 'desc')->with('user')->where('status',2)->filter(request(['search']))->paginate($request->per_page ?? 5);
            $noti = Ticket::where('status',2)->OrderBy('id','desc')->count();
        }

        return response()->json(['data'=>$form,'noti'=>$noti]);
    }

    public function checked($id){
        try {
           $ids = explode(",", $id);
           $tickets = Ticket::whereIn('id', $ids)->where('status',0)->get();
           foreach ($tickets as $value) {
             $value->status = 2;
             $value->save();
             $logCreate = Log::create(['title' => 'checked','user_id'=>request()->user()->id,'token' => $value['ticket']]);
           }

           if ($logCreate) return response()->json(['response' => 'success']);
           else return response()->json(['response' => 'error']);

           return response()->json(['response'=>'success',$tickets]);
        }catch (\Throwable $th) {
           return response()->json($th);
        }
     }

     public function approved($id){
        try {
           $ids = explode(",", $id);
           $tickets = Ticket::whereIn('id', $ids)->where('status',2)->get();
           foreach ($tickets as $value) {
             $value->status = 3;
             $value->save();
             $logCreate = Log::create(['title' => 'approved','user_id'=>request()->user()->id,'token' => $value['ticket']]);
           }

           if ($logCreate) return response()->json(['response' => 'success']);
           else return response()->json(['response' => 'error']);

           return response()->json(['response'=>'success',$tickets]);
        }catch (\Throwable $th) {
           return response()->json($th);
        }
     }


    public function edit(Request $request){

        $ticket = Ticket::find($request->id);

        if($request->code == 1) $message = 'rejected';
        else if($request->code == 3) $message = 'approved';
        else  $message = 'checked';

        if($ticket){
            $ticket->status = $request->code;
            $ticket->save();

            $logCreate = Log::create(['title' => $message,'user_id'=>$request->user()->id,'token' => $ticket['ticket']]);
            if ($logCreate) return response()->json(['response' => 'success']);
            else return response()->json(['response' => 'error']);

        }else return response()->json(['response' => 'error']);
    }

    public function show($id)
    {
        $tickets = Ticket::find($id);
        return response()->json(['response' => 'success', 'tickets' => $tickets]);
    }

    public function destory(Request $request)
    {
        $token = Ticket::find($request->id);
        $delete = $token->delete();
        if ($delete) {
            $logCreate = Log::create(['title' => 'deleted','user_id'=>$request->user()->id,'token' => $token['ticket']]);
            if ($logCreate) return response()->json(['response' => 'success']);
            else return response()->json(['response' => 'error']);
        }
        else return response()->json(['response' => 'Id not found.']);
    }
}
