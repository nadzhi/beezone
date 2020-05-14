@extends('frontend.layouts.app')

@section('content')

    <div id="page-content">
        <section class="slice-xs sct-color-2 border-bottom">
            <div class="container container-sm">
                <div class="row cols-delimited">
                    <div class="col-4">
                        <div class="icon-block icon-block--style-1-v5 text-center">
                            <div class="block-icon c-gray-light mb-0">
                                <i class="la la-shopping-cart"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">1. {{__('My Cart')}}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="icon-block icon-block--style-1-v5 text-center">
                            <div class="block-icon c-gray-light mb-0">
                                <i class="la la-truck"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">2. {{__('Shipping info')}}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="icon-block icon-block--style-1-v5 text-center active">
                            <div class="block-icon mb-0">
                                <i class="la la-credit-card"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">3. {{__('Payment')}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>




        <section class="py-3 gry-bg">
            <div class="container">
                <div class="row cols-xs-space cols-sm-space cols-md-space">
                    <div class="col-lg-8">
                        <form action="{{ route('payment.checkout') }}" class="form-default" data-toggle="validator" role="form" method="POST" id="checkout-form">
                            @csrf
                            <div class="card">
                                <div class="card-title px-4 py-3">
                                    <h3 class="heading heading-5 strong-500">
                                        {{__('Select a payment option')}}
                                    </h3>
                                </div>
                                <div class="card-body text-center">
                                    <div class="row">
                                        <div class="col-md-6 mx-auto">
                                            <div class="row">
												<div class="col-6">
													<label class="payment_option mb-4" data-toggle="tooltip" data-title="Оплата при доставке">
														<input type="radio" id="" name="payment_option" value="cash_on_delivery" checked>
														<span>
															<img src="{{ asset('frontend/images/icons/cards/cod.png')}}" class="img-fluid">
														</span>
													</label>
												</div>
												<div class="col-6">
													<label class="payment_option mb-4" data-toggle="tooltip" data-title="С картой">
														<input type="radio" id="" name="payment_option" value="credit_card">
														<span>
															<img src="{{ asset('frontend/images/icons/cards/cart.jpg')}}" class="img-fluid">
														</span>
													</label>
												</div>
												<div class="col-6">
													<label class="payment_option mb-4" data-toggle="tooltip" data-title="Каспи Перевод">
														<input type="radio" id="" name="payment_option" value="kaspi">
														<span>
															<img src="{{ asset('frontend/images/icons/cards/kaspi.png')}}" class="img-fluid">
														</span>
													</label>
												</div>
												<div class="col-6">
													<label class="payment_option mb-4" data-toggle="tooltip" data-title="Консигнация">
														<input type="radio" id="" name="payment_option" value="consignation">
														<span>
															<img src="{{ asset('frontend/images/icons/cards/consignation.jpg')}}" class="img-fluid">
														</span>
													</label>
												</div>
												
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center pt-4">
                                <div class="col-6">
                                    <a href="{{ route('home') }}" class="link link--style-3">
                                        <i class="ion-android-arrow-back"></i>
                                        {{__('Return to shop')}}
                                    </a>
                                </div>
                                <div class="col-6 text-right">
                                    <button type="submit" class="btn btn-styled btn-base-1">{{__('Complete Order')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-4 ml-lg-auto">
                        @include('frontend.partials.cart_summary')
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function use_wallet(){
            $('input[name=payment_option]').val('wallet');
            $('#checkout-form').submit();
        }
    </script>
@endsection
