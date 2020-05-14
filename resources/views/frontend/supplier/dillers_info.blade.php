<div class="modal-header">
    <h5 class="modal-title strong-600 heading-5">
		{{ $user->name }}
	</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="p-4" style="background-color: #efefef">

	<!-- dashboard content -->
	<div class="">
		<div class="row">
			<div class="col-md-3 col-6">
				<div class="dashboard-widget text-center green-widget mt-4 c-pointer">
					<a href="javascript:;" class="d-block">
						<i class="fa fa-upload"></i>
						<span class="d-block title heading-3 strong-400">{{ count(\App\SupplierProduct::where('user_id', $user->id)->get()) }}</span>
						<span class="d-block sub-title">{{__('Products')}}</span>
					</a>
				</div>
			</div>
			<div class="col-md-3 col-6">
				<div class="dashboard-widget text-center red-widget mt-4 c-pointer">
					<a href="javascript:;" class="d-block">
						<i class="fa fa-cart-plus"></i>
						<span class="d-block title heading-3 strong-400">{{ count(\App\OrderDetail::where('seller_id', $user->id)->where('delivery_status', 'delivered')->get()) }}</span>
						<span class="d-block sub-title">{{__('Total sale')}}</span>
					</a>
				</div>
			</div>
			<div class="col-md-3 col-6">
				<div class="dashboard-widget text-center blue-widget mt-4 c-pointer">
					<a href="javascript:;" class="d-block">
						<i class="fa fa-dollar"></i>
						@php
						$orderDetails = \App\OrderDetail::where('seller_id', $user->id)->get();
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
						<span class="d-block sub-title">{{__('Total earnings')}}</span>
					</a>
				</div>
			</div>
			<div class="col-md-3 col-6">
				<div class="dashboard-widget text-center yellow-widget mt-4 c-pointer">
					<a href="javascript:;" class="d-block">
						<i class="fa fa-check-square-o"></i>
						<span class="d-block title heading-3 strong-400">{{ count(\App\OrderDetail::where('seller_id', $user->id)->where('delivery_status', 'delivered')->get()) }}</span>
						<span class="d-block sub-title">{{__('Successful orders')}}</span>
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
								<td><strong class="heading-6">{{ count(\App\OrderDetail::where('seller_id', $user->id)->get()) }}</strong></td>
							</tr>
							<tr >
								<td>{{__('Pending orders')}}:</td>
								<td><strong class="heading-6">{{ count(\App\OrderDetail::where('seller_id', $user->id)->where('delivery_status', 'pending')->get()) }}</strong></td>
							</tr>
							<tr >
								<td>{{__('Cancelled orders')}}:</td>
								<td><strong class="heading-6">{{ count(\App\OrderDetail::where('seller_id', $user->id)->where('delivery_status', 'cancelled')->get()) }}</strong></td>
							</tr>
							<tr >
								<td>{{__('Successful orders')}}:</td>
								<td><strong class="heading-6">{{ count(\App\OrderDetail::where('seller_id', $user->id)->where('delivery_status', 'delivered')->get()) }}</strong></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="form-box bg-white mt-4">
					<div class="form-box-title px-3 py-2 text-center">
						Данные диллера
					</div>
					<div class="form-box-content p-3">
						<table class="table mb-0 table-bordered" style="font-size:14px;">
							<tr>
								<td>Email:</td>
								<td><strong class="heading-6">{{ $user->email }}</strong></td>
							</tr>
							<tr>
								<td>Телефон:</td>
								<td><strong class="heading-6">{{ $user->phone }}</strong></td>
							</tr>
							<tr>
								<td>Страна:</td>
								<td><strong class="heading-6">{{ $user->country }}</strong></td>
							</tr>
							<tr >
								<td>Город:</td>
								<td><strong class="heading-6">{{ $user->city }}</strong></td>
							</tr>
							<tr >
								<td>Адрес:</td>
								<td><strong class="heading-6">{{ $user->address }}</strong></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-box bg-white mt-4">
					<div class="form-box-title px-3 py-2 text-center">
						Последняя покупка
					</div>
					<div class="form-box-content p-3">
						<table class="table mb-0 table-bordered" style="font-size:14px;">
							@php $last_order = \App\Order::where('user_id', $user->id)->orderBy('id','desc')->first(); @endphp
							<tr>
								<td>Сумма:</td>
								<td><strong class="heading-6"><?php if(isset($last_order)): ?> {{ $last_order->grand_total }} <?php else: ?> - <?php endif; ?></strong></td>
							</tr>
							<tr >
								<td>Дата:</td>
								<td><strong class="heading-6"><?php if(isset($last_order)): ?> {{ $last_order->created_at }} <?php else: ?> - <?php endif; ?></strong></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="form-box bg-white mt-4">
					<div class="form-box-title px-3 py-2 text-center">
						Диллеры
					</div>
					<div class="form-box-content p-3">
						<table class="table mb-0 table-bordered" style="font-size:14px;">
							<tr >
								<td>Количество:</td>
								<td><strong class="heading-6">{{ count(\App\User::where('referal', $user->id)->get()) }}</strong></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="bg-white mt-4 p-4">
					<div class="heading-4 strong-700 text-center">Уровень</div>
					<p></p>
					<?php 
					$my_levels = \App\SupplierLevel::where("user_id", $user->id)->get();
					?>
					<?php if(count($my_levels) > 0): ?>
					<?php foreach($my_levels as $level): ?>
					<?php $brand = \App\Brand::where("id", $level->brand_id)->first(); ?>
					<h6 style="border-bottom:1px solid #eaeaea;padding:6px 0;margin:0 30px;"><?php echo $brand->name; ?> 
						<span style="float:right;background: #fec200;font-size:14px;width:25px;height:25px;border-radius:100%;margin-top:-2px;text-align:center;font-weight:bold;padding-top:3px">
							<?php echo $level->level; ?>
						</span>
					</h6>
					<?php endforeach; ?>
					<?php else: ?>
					<div class="text-center">Нет данные</div>
					<?php endif; ?>
				</div>
			</div>
		</div>




	</div>
</div>