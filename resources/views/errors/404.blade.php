@extends('layouts.blank')

@section('content')
<div class="text-center">
    <h1 class="error-code text-danger">{{__('404')}}</h1>
    <p class="h4 text-uppercase text-bold">{{__('Страница не найдена!')}}</p>
    <div class="pad-btm">
        {{__('Извините, но страница, которую вы ищете, не была найдена на нашем сервере.')}}
    </div>
    <hr class="new-section-sm bord-no">
    <div class="pad-top"><a class="btn btn-primary" href="{{env('APP_URL')}}">{{__('Return Home')}}</a></div>
</div>
@endsection
