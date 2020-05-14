@extends('layouts.blank')

@section('content')
<div class="text-center">
    <h1 class="error-code text-danger">{{__('500')}}</h1>
    <p class="h4 text-uppercase text-bold">{{__('УПС!')}}</p>
    <div class="pad-btm">
        {{__('Что-то пошло не так. Похоже, сервер не смог загрузить ваш запрос.')}}
    </div>
    <hr class="new-section-sm bord-no">
    <div class="pad-top"><a class="btn btn-primary" href="{{env('APP_URL')}}">{{__('Return Home')}}</a></div>
</div>
@endsection
