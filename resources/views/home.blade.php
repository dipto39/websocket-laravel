@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
               
                <div class="my-5 border-2">
                    <h1 class="text-center p-3 border-bottom">Chat Room ({{Auth::user()->name}})</h1>
                    <div class="p-3 overflow-auto h-[300px]" id="messages">
                            @foreach ($messages as $m)
                                <h1 class="p-1 {{$m->user_id == Auth::user()->id ? 'text-right' : ''}}"><span class="font-bold">{{$m->name}} : </span>{{$m->message}} </h1>                            
                            @endforeach
                       
                            {{-- <h1 class="p-1">{{$m->message}} </h1> --}}
                    </div>
                    <form action="" method="post" id="myForm" class="flex">
                        @csrf
                        <input name="user_id" id="user_id" value="{{ Auth::user()->id }}" class="hidden">
                        <input name="name" id="name" value="{{ Auth::user()->name }}" class="hidden">
                        <input type="text" name="message" id="message" class="w-full border px-2 outline-none">
                        <button type="submit"  id="submitBtn" class="btn btn-primary ">send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
