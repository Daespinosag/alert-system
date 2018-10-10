@extends('template.main')

@section('content')
    <div id="app">
        <router-view @prop('user', $user)></router-view>
    </div>
@endsection

@section('javascript')

@endsection