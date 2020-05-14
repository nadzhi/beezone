@extends('frontend.layouts.app')

@section('content')
	<?php if(Session::has('order_id')): ?>
    <section class="gry-bg py-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="card">
                        <div class="align-items-center card-header d-flex justify-content-center text-center" >
                            <h3 class="d-inline-block heading-4 mb-0 mr-3 strong-600" >
								<?php if($payment_type == "kaspi"): ?> Перевод на каспи <?php endif; ?>
								<?php if($payment_type == "credit_card"): ?> Оплата с картой <?php endif; ?>
							</h3>
                        </div>
                        <div class="card-body">
							<div class='row'>
								<div class='col-md-12'><div class="alert alert-success">Ваш заказ успешно оформлен!</div></div>
								<div class='col-md-6'>
									
									<?php 
										if($payment_type == "kaspi"):
											$name = "kaspi";
										elseif($payment_type == "credit_card"):
											$name = "card";
										endif;
										if(!is_null(Auth::user()->referal)): 
											$user_id = Auth::user()->referal; 
											$user_payments = \App\UserPayment::where([["user_id",$user_id],["name",$name]])->get();
										endif;
										
									?>
									
									<p>Переводите сумму: </p>
									<?php if(isset($user_payments)): ?>
										<?php foreach($user_payments as $user_payment): ?>
											<h4>{{ $user_payment->user_name }}</h4>
											<h5>{{ $user_payment->value }}</h5>
										<?php endforeach; ?>
									<?php else: ?>
										<?php $generalsetting = \App\GeneralSetting::first(); ?>
										<h4>{{ $generalsetting->user_name }}</h4>
										<?php if($payment_type == "kaspi"): ?>
											<h5>{{ $generalsetting->kaspi }}</h5>
										<?php elseif($payment_type == "credit_card"): ?>
											<h5>{{ $generalsetting->card }}</h5>
										<?php endif; ?>
									<?php endif; ?>
								</div>
								<div class='col-md-6 text-md-right'>
									<p>&nbsp;</p>
										@php
											$order = \App\Order::findOrFail($order_id);
											$total = $order->grand_total;
										@endphp
									<h6>Сумма: </h6>
									<h3>{{ single_price($total) }}</h3>
								</div>
							</div>
							<div class="row align-items-center pt-4">
                                <div class="col-12 text-center pt-4">
                                    <a href="{{ route('home') }}" class="btn btn-styled btn-base-1">
                                        <i class="ion-android-arrow-back"></i>
                                        {{__('Return to shop')}}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php else: ?>
<section class="gry-bg py-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="card">
                        <div class="align-items-center card-header d-flex justify-content-center text-center" >
                            <h3 class="d-inline-block heading-4 mb-0 mr-3 strong-600" >
								Ошибка!
							</h3>
                        </div>
                        <div class="card-body">
							<div class='row'>
								<div class='col-md-12 text-center'>
									<h6>Заказ не найден!</h6>
								</div>
							</div>
							<div class="row align-items-center pt-4">
                                <div class="col-12 text-center pt-4">
                                    <a href="{{ route('home') }}" class="btn btn-styled btn-base-1">
                                        <i class="ion-android-arrow-back"></i>
                                        {{__('Return to shop')}}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

@endsection

