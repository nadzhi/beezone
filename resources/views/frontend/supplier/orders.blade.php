@extends('frontend.layouts.app')

@section('content')

    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-lg-block">
                    @include('frontend.inc.supplier_side_nav')
                </div>

                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        {{__('Orders')}}
                                    </h2>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{__('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{__('Dashboard')}}</a></li>
                                            <li class="active"><a href="{{ route('orders.index') }}">{{__('Orders')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (count($orders) > 0)
                            <!-- Order history table -->
                            <div class="card no-border mt-4">
                                <div>
                                    <table class="table table-sm table-hover table-responsive-md">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{__('Order Code')}}</th>
                                                <th>{{__('Num. of Products')}}</th>
                                                <th>{{__('Customer')}}</th>
                                                <th>{{__('Amount')}}</th>
                                                <th>{{__('Delivery Status')}}</th>
                                                <th>{{__('Payment Status')}}</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $key => $order_id)
                                                @php
                                                    $order = \App\Order::find($order_id->id);
                                                @endphp
                                                @if($order != null)
                                                    <tr>
                                                        <td>
                                                            {{ $key+1 }}
                                                        </td>
                                                        <td>
                                                            <a href="#{{ $order->code }}" onclick="show_order_details({{ $order->id }})">{{ $order->code }}</a>
                                                        </td>
                                                        <td>
                                                            {{ count($order->orderDetails->where('seller_id', Auth::user()->id)) }}
                                                        </td>
                                                        <td>
                                                            @if ($order->user_id != null)
                                                                {{ $order->user->name }}
                                                            @else
                                                                Guest ({{ $order->guest_id }})
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ single_price($order->orderDetails->where('seller_id', Auth::user()->id)->sum('price')) }}
                                                        </td>
                                                        <td>
                                                            @php
                                                                $status = $order->orderDetails->first()->delivery_status;
                                                            @endphp
                                                            {{ __(ucfirst(str_replace('_', ' ', $status))) }}
                                                        </td>
                                                        <td>
                                                            <span class="badge badge--2 mr-4">
                                                                @if ($order->orderDetails->where('seller_id', Auth::user()->id)->first()->payment_status == 'paid')
                                                                    <i class="bg-green"></i> {{__('Paid')}}
                                                                @elseif($order->orderDetails->where('seller_id', Auth::user()->id)->first()->payment_status == 'consignation')
																	<i class="bg-green"></i> Консигнация
                                                                @else
                                                                    <i class="bg-red"></i> {{__('Unpaid')}}
                                                                @endif
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button class="btn" type="button" id="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fa fa-ellipsis-v"></i>
                                                                </button>

                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="">
                                                                    <button onclick="show_order_details({{ $order->id }})" class="dropdown-item">{{__('Order Details')}}</button>
                                                                    <a href="{{ route('seller.invoice.download', $order->id) }}" class="dropdown-item">{{__('Download Invoice')}}</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <div class="pagination-wrapper pt-4">
                            <ul class="pagination justify-content-end">
                                {{ $orders->links() }}
                            </ul>
                        </div>
						
						<?php if (count($orders) > 0 AND is_null(Auth::user()->referal)): ?>
							<div align="right">
								<div class="btn btn-primary" data-toggle="modal" data-target="#combine_order_modal" id="combine_order">Отправить заказ на склад</div>
							</div>
						<?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>


<div class="modal fade" id="order_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom dillers-modal" id="modal-size" role="document">
		<div class="modal-content position-relative">
			<div class="c-preloader">
				<i class="fa fa-spin fa-spinner"></i>
			</div>
			<div id="order-details-modal-body">

			</div>
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="combine_order_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="exampleModalLabel">Отправить заказ на склад</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="POST" action="{{ route('orders.combine') }}" >
				@csrf
				@if (count($orders) > 0)
					<div class="modal-body">
						<div class="no-gutters mt-2 mb-2 pb-3" style="border-bottom:1px solid #eaeaea;">
							@foreach ($orders as $key => $order_id)
								@php
									$order = \App\Order::find($order_id->id);
								@endphp
								@if($order != null)
									<div style="display:block;">
										<label>
											<input type="checkbox" name="orders[]" value="{{ $order->id }}" />
											<span style="font-size:15px;">Заказ №{{ $order->code }}</span>
										</label>
									</div>
								@endif
							@endforeach
						</div>
						<label>
							<input type="checkbox" name="is_combine" value="1" />
							<span>Объединить заказ</span>
						</label>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('cancel')}}</button>
						<button type="submit" class="btn btn-primary" >Отправить</button>
					</div>
				@else
					<p align="center">Невозможно выполнить эту функцию</p>
				@endif
			</form>
		</div>
	</div>
</div>

@endsection
