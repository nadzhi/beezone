@extends('frontend.layouts.app')

@section('content')
    <section class="gry-bg py-5">
        <div class="profile">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 offset-xl-3">
                        <div class="card">
                            <div class="text-center px-35 pt-5">
                                <h3 class="heading heading-4 strong-500">
                                    {{__('Login to your account.')}}
                                </h3>
                            </div>
                            <div class="px-5 py-3 py-lg-5">
                                <div class="row align-items-center">
                                    <div class="col-12 mb-3" onselectstart="return false;">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="phone-radio" value="option1" checked="" data-toggle="collapse">
                                            <label class="form-check-label strong-500" for="phone-radio" style="cursor: pointer;">Вход по телефону</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="email-radio" value="option2" data-toggle="collapse">
                                            <label class="form-check-label strong-500" for="email-radio" style="cursor: pointer;">Вход по email</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg">
                                        <form class="form-default" role="form" action="{{ route('login') }}" method="POST">
                                            @csrf
											@if($errors->has('email') || $errors->has('phone') || $errors->has('password'))
												<div class="alert alert-danger" role="alert">
													<strong>{{ __('Incorrect Login') }}</strong>
												</div>
											@endif
                                            <div class="row" id="email-block" style="display: none;">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <!-- <label>{{ __('email') }}</label> -->
                                                        <div class="input-group input-group--style-1">
                                                            <input type="email" class="form-control form-control-sm {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{__('Email')}}" name="email" id="email">
                                                            <span class="input-group-addon">
                                                                <i class="text-md la la-user"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="phone-block">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                    <!-- <label>{{ __('phone') }}</label> -->
                                                        <div class="input-group input-group--style-1">
                                                            <input type="text" class="form-control form-control-sm {{ $errors->has('phone') ? ' is-invalid' : '' }}" value="{{ old('phone') }}" placeholder="{{__('Phone')}}" name="phone" id="phone">
                                                            <span class="input-group-addon">
                                                                <i class="text-md la la-user"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <!-- <label>{{ __('password') }}</label> -->
                                                        <div class="input-group input-group--style-1">
                                                            <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{__('Password')}}" name="password" id="password">
                                                            <span class="input-group-addon">
                                                                <i class="text-md la la-lock"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="checkbox pad-btm text-left">
                                                            <input id="demo-form-checkbox" class="magic-checkbox" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                            <label for="demo-form-checkbox" class="text-sm">
                                                                {{ __('Remember Me') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if(env('MAIL_USERNAME') != null && env('MAIL_PASSWORD') != null)
                                                    <div class="col-6 text-right">
                                                        <a href="{{ route('password.request') }}" class="link link-xs link--style-3">{{__('Forgot password?')}}</a>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="row">
                                                <div class="col text-center">
                                                    <button type="submit" class="btn btn-styled btn-base-1 btn-md w-100">{{ __('Login') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center px-35 pb-3">
                                <p class="text-md">
                                    {{__('Need an account?')}}<br/> <a href="{{ route('user.registration') }}" class="strong-600">{{__('Register Now')}}</a>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

