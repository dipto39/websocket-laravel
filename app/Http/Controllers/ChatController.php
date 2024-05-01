<?php

namespace App\Http\Controllers;

use App\Events\PrivateMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function search(Request $request)
    {
       $data = DB::table('users')->where('name','like','%'.$request->search.'%')->whereNot('id',Auth::id())->get();
       return response()->json($data);
       
    }

    public function messages(Request $request){
        // return "userId"+$request->userId+"receiverId"+$request->receiverId;
        $data = DB::table('chats')
        ->where(function($query) use ($request) {
            $query->where('user_id', Auth::id())
                  ->where('receiver_id', $request->user_id);
        })
        ->orWhere(function($query) use ($request) {
            $query->where('user_id', $request->user_id)
                  ->where('receiver_id', Auth::id());
        })
        ->get();
        return response()->json($data);
    }

    public function sendMessage(Request $request)
    {
        DB::table('chats')->insert([
            'user_id' => $request->user_id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message
        ]);
        event(new PrivateMessage(['message' => $request->message, 'userId' => $request->user_id,  'receiverId' => $request->receiver_id], $request->receiver_id));

        return response()->json(['message' => $request->message]);
    }
}
