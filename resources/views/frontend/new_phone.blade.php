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
                                    {{__('View phone')}}
                                </h3>
                                <h6>
                                    {{__('Sms code will sent to your phone')}}
                                </h6>
                            </div>
                            <div class="px-5 py-3 py-lg-5">
                                <div class="row align-items-center">
                                    <div class="col-12 col-lg">
                                        <form class="form-default" role="form" action="{{ route('phone.new') }}" method="POST">
                                            @csrf

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                    <!-- <label>{{ __('phone') }}</label> -->
                                                        <div class="input-group input-group--style-1">
                                                            <input type="text" class="form-control form-control-sm {{ $errors->has('phone') ? ' is-invalid' : '' }}" value="{{ old('phone') }}" placeholder="{{__('Phone')}}" id="phone" name="phone" required>
                                                            <span class="input-group-addon">

                                                                <i class="text-md la la-mobile"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col text-center">
                                                    <button type="submit" class="btn btn-styled btn-base-1 btn-md w-100">{{ __('Confirm now') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
