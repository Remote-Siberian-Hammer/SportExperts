@extends('layouts.notification')
@section('content')
    <div style="background-color: #610808; color: #fff">
        <br>
        <h1 style="text-align: center;">Вас пригласили на событие!</h1>
        <a href="/registration/?ivite_user_id={{ $attributes['ivite_user_id'] }}&&event_id={{ $attributes['event_id'] }}">Записаться</a>
        <span>{{ $personalUrl }}</span>
        <br>
    </div>
@endsection
