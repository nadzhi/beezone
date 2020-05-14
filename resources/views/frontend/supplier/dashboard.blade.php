@extends('frontend.layouts.app')
@section('content')

    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-lg-block">
                    @include('frontend.inc.supplier_side_nav')
                </div>

                <div class="col-lg-9">
                    <!-- Page title -->
                    <div class="page-title">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                    {{__('Dashboard')}}
                                </h2>
                            </div>
                            <div class="col-md-6">
                                <div class="float-md-right">
                                    <ul class="breadcrumb">
                                        <li><a href="{{ route('home') }}">{{__('Home')}}</a></li>
                                        <li class="active"><a href="{{ route('dashboard') }}">{{__('Dashboard')}}</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- dashboard content -->
                    <div class="">
                        <div class="row">
							
                            <div class="col-md-3 col-6">
                                <div class="dashboard-widget text-center blue-widget mt-4 c-pointer">
                                    <a href="javascript:;" class="d-block">
                                        <i class="fa fa-dollar"></i>
                                        @php
                                            $orderDetails = \App\OrderDetail::where('seller_id', Auth::user()->id)->get();
											
                                            $total = 0;
											if(isset($orderDetails)){
												foreach ($orderDetails as $key => $orderDetail) {
													if(isset($orderDetail->order->payment_status)){
														if($orderDetail->order->payment_status == 'paid'){
															$total += $orderDetail->price;
														}
													}
												}
											}
                                        @endphp
                                        <span class="d-block title heading-3 strong-400">{{ single_price($total) }}</span>
                                        <span class="d-block sub-title">Мои продажи</span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="dashboard-widget text-center green-widget mt-4 c-pointer">
                                    <a href="javascript:;" class="d-block">
                                        <i class="fa fa-dollar"></i>
                                        @php
                                            $orderDetails = \App\OrderDetail::where('seller_id', Auth::user()->id)->get();
											
                                            $total = 0;
											if(isset($orderDetails)){
												foreach ($orderDetails as $key => $orderDetail) {
													if(isset($orderDetail->order->payment_status)){
														if($orderDetail->order->payment_status == 'paid'){
															$total += $orderDetail->price;
														}
													}
												}
											}
										
										
											$orders = \App\Order::where('user_id', Auth::user()->id)->get();
											$total2 = 0;
											if(isset($orders)){
												foreach ($orders as $key => $order) {
													$orderDetails = \App\OrderDetail::where('order_id', $order->id)->get();
													if(isset($orderDetails)){
														foreach ($orderDetails as $key => $orderDetail) {
															if(isset($orderDetail->payment_status)){
																if($orderDetail->payment_status == 'paid'){
																	$total2 += $orderDetail->price;
																}
															}
														}
													}
												}
											}
                                        @endphp
										<span class="d-block title heading-3 strong-400">{{ single_price($total-$total2) }}</span>
                                        <span class="d-block sub-title">Моя прибыль</span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="dashboard-widget text-center red-widget mt-4 c-pointer">
                                    <a href="javascript:;" class="d-block">
                                        <i class="fa fa-dollar"></i>
										@php
										$orderDetails = \App\OrderDetail::where('seller_id', Auth::user()->id)->get();
										$total = 0;
										if(isset($orderDetails)){
											foreach ($orderDetails as $key => $orderDetail) {
												if(isset($orderDetail->payment_status)){
													if($orderDetail->payment_status == 'consignation'){
														$total += $orderDetail->price;
													}
												}
											}
										}
										@endphp
                                        <span class="d-block title heading-3 strong-400">{{ single_price($total) }}</span>
                                        <span class="d-block sub-title">Мне должны</span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="dashboard-widget text-center yellow-widget mt-4 c-pointer">
                                    <a href="javascript:;" class="d-block">
                                        <i class="fa fa-dollar"></i>
										@php
										$orders = \App\Order::where('user_id', Auth::user()->id)->get();
										if(isset($orders)){
										foreach ($orders as $key => $order) {
										$orderDetails = \App\OrderDetail::where('order_id', $order->id)->get();
										$total = 0;
										if(isset($orderDetails)){
										foreach ($orderDetails as $key => $orderDetail) {
										if(isset($orderDetail->payment_status)){
										if($orderDetail->payment_status == 'consignation'){
										$total += $orderDetail->price;
										}
										}
										}
										}
										}
										}

										@endphp
                                        <span class="d-block title heading-3 strong-400">{{ single_price($total) }}</span>
                                        <span class="d-block sub-title">Я должен</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-box bg-white mt-4">
                                    <div class="form-box-title px-3 py-2 text-center">
                                        {{__('Orders')}}
                                    </div>
                                    <div class="form-box-content p-3">
                                        <table class="table mb-0 table-bordered" style="font-size:14px;">
                                            <tr>
                                                <td>{{__('Total orders')}}:</td>
                                                <td><strong class="heading-6">{{ count(\App\OrderDetail::where('seller_id', Auth::user()->id)->get()) }}</strong></td>
                                            </tr>
                                            <tr >
                                                <td>{{__('Pending orders')}}:</td>
                                                <td><strong class="heading-6">{{ count(\App\OrderDetail::where('seller_id', Auth::user()->id)->where('delivery_status', 'pending')->get()) }}</strong></td>
                                            </tr>
                                            <tr >
                                                <td>{{__('Cancelled orders')}}:</td>
                                                <td><strong class="heading-6">{{ count(\App\OrderDetail::where('seller_id', Auth::user()->id)->where('delivery_status', 'cancelled')->get()) }}</strong></td>
                                            </tr>
                                            <tr >
                                                <td>{{__('Successful orders')}}:</td>
                                                <td><strong class="heading-6">{{ count(\App\OrderDetail::where('seller_id', Auth::user()->id)->where('delivery_status', 'delivered')->get()) }}</strong></td>
                                            </tr>
											
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
								<?php $referal = \App\User::where('id', Auth::user()->referal)->first(); ?>
								<?php if($referal): ?>
                                <div class="form-box bg-white mt-4 text-center">
                                    <div class="form-box-title px-3 py-2 text-center">
                                        Ваша реферал:
                                    </div>
                                    <div class="form-box-content p-3">
                                        <strong><?php echo $referal->name ?></strong>
                                    </div>
                                </div>
								<?php endif; ?>
                                <div class="form-box bg-white mt-4 text-center" style="overflow-y:auto">
                                    <div class="form-box-title px-3 py-2 text-center">
                                        Ваша реферальная ссылка:
                                    </div>
                                    <div class="form-box-content p-3">
                                        <a id="ref-link" href="{{route('user.registration_referal', Auth::user()->hash)}}">{{route('user.registration_referal', Auth::user()->hash)}}</a>
										<button type="button" id="copy-ref" class="my-3 btn btn-info">Скопировать</button>
                                    </div>
                                </div>
								<div class="bg-white mt-4 p-4">
									<div class="heading-4 strong-700 text-center">Мой уровень</div>
									<p></p>
									<?php 
										$my_levels = \App\SupplierLevel::where("user_id", Auth::user()->id)->get();
									?>
									<?php if(count($my_levels) > 0): ?>
										<?php foreach($my_levels as $level): ?>
											<?php $brand = \App\Subbrand::where("id", $level->brand_id)->first(); ?>
											<p style="border-bottom:1px solid #eaeaea;padding:6px 0;margin:0 30px;font-size: 14px"><?php echo $brand->name; ?> 
												<span style="float:right;background: #fec200;font-size:14px;width:25px;height:25px;border-radius:100%;margin-top:-2px;text-align:center;font-weight:bold;padding-top:3px">
													<?php echo $level->level; ?>
												</span>
											</p>
										<?php endforeach; ?>
									<?php else: ?>
										<div class="text-center">Нет данные</div>
									<?php endif; ?>
								</div>
                            </div>
                        </div>
						
						
						
						
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script type="text/javascript">
		$(document).ready(function(){
			$("#copy-ref").on("click",function(){
				window.getSelection().removeAllRanges();
				var link = document.getElementById('ref-link'); 
				var range = document.createRange();
				range.selectNode(link); 
				window.getSelection().addRange(range);
				try { 
					document.execCommand('copy');
					alert("Скопирован");
				} catch(err) { 
					alert("Не смог скопировать ");
				} 
				
				window.getSelection().removeAllRanges();
			});
				
		});
    </script>
@endsection

