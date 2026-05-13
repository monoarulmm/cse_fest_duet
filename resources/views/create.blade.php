@extends('layouts.app')

@section('content')
    <h1>Hello</h1>


    <form method="POST"  action="{{route('shurjopay.lara')}}">
        @csrf


        <input class="favorite styled" type="submit" value="Submit">
    </form>
@endsection
