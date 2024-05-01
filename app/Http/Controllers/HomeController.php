<?php

namespace App\Http\Controllers;

use App\Events\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $messages = DB::table('rooms')->get();
        $data = compact('messages');
        return view('home')->with( $data);
    }

    public function chat()
    {
        $users = DB::table('users')->whereNotIn('id', [auth()->user()->id])->get();
        $data = compact('users');
        return view('chat')->with($data);
    }
    public function store(Request $request)
    {
        // return "ok";
        $data =  $request->all();

        DB::table('rooms')->insert([
            'name' => $data['name'],
            'message' => $data['message'],
            'user_id' => $data['user_id'],
        ]);
        event(new SendMessage($data['message'],$data['user_id'],$data['name']));
        return response()->json($data);
    }
}
