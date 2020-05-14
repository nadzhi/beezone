<div style="margin-left:auto;margin-right:auto;">
<style media="all">
	*{
		margin: 0;
		padding: 0;
		line-height: 1.5;
		font-family: DejaVu Sans;
		color: #333542;
	}
	div{
		font-size: 1rem;
	}
	.gry-color *,
	.gry-color{
		color:#878f9c;
	}
	table{
		width: 100%;
	}
	table th{
		font-weight: normal;
	}
	table.padding th{
		padding: .5rem .7rem;
	}
	table.padding td{
		padding: .7rem;
	}
	table.sm-padding td{
		padding: .2rem .7rem;
	}
	.border-bottom td,
	.border-bottom th{
		border-bottom:1px solid #eceff4;
	}
	.text-left{
		text-align:left;
	}
	.text-right{
		text-align:right;
	}
	.small{
		font-size: .85rem;
	}
	.strong{
		font-weight: bold;
	}
</style>

	@php
		$generalsetting = \App\GeneralSetting::first();
	@endphp

	<div style="background: #eceff4;padding: 1.5rem;">
		<table>
			<tr>
				<td>
					@if($generalsetting->logo != null)
						<img src="{{ asset($generalsetting->logo) }}" height="40" style="display:inline-block;">
					@else
						<img src="{{ asset('frontend/images/logo/logo.png') }}" height="40" style="display:inline-block;">
					@endif
				</td>
				<td style="font-size: 2.5rem;" class="text-right strong">Cчет-фактура</td>
			</tr>
		</table>
		<table>
			<tr>
				<td style="font-size: 1.2rem;" class="strong">{{ $generalsetting->site_name }}</td>
				<td class="text-right"></td>
			</tr>
			<tr>
				<td class="gry-color small">{{ $generalsetting->address }}</td>
				<td class="text-right"></td>
			</tr>
			<tr>
				<td class="gry-color small">Email: {{ $generalsetting->email }}</td>
				<td class="text-right small"><span class="gry-color small">Номер Заказа:</span> <span class="strong">{{ $order->code }}</span></td>
			</tr>
			<tr>
				<td class="gry-color small">Телефон: {{ $generalsetting->phone }}</td>
				<td class="text-right small"><span class="gry-color small">Дата заказаe:</span> <span class=" strong">{{ date('d-m-Y', $order->date) }}</span></td>
			</tr>
		</table>

	</div>

	<div style="border-bottom:1px solid #eceff4;margin: 0 1.5rem;"></div>

	<div style="padding: 1.5rem;">
		<table>
			@php
				$shipping_address = json_decode($order->shipping_address);
			@endphp
			<tr><td class="strong small gry-color">Счет:</td></tr>
			<tr><td class="strong">{{ $shipping_address->name }}</td></tr>
			<tr><td class="gry-color small">{{ $shipping_address->address }}, {{ $shipping_address->city }}, {{ $shipping_address->country }}</td></tr>
			<tr><td class="gry-color small">Email: {{ $shipping_address->email }}</td></tr>
			<tr><td class="gry-color small">Телефон: {{ $shipping_address->phone }}</td></tr>
		</table>
	</div>

    <div style="padding: 1.5rem;">
		<table class="padding text-left small border-bottom">
			<thead>
                <tr class="gry-color" style="background: #eceff4;">
                    <th width="50%">Товар</th>
                    <th width="10%">Кол-во</th>
                    <th width="15%">Цена</th>
                    <th width="15%" class="text-right">Всего</th>
                </tr>
			</thead>
			<tbody class="strong">
                @foreach ($order->orderDetails as $key => $orderDetail)
	                <tr class="">
						<td>{{ $orderDetail->product->name }}</td>
						<td class="gry-color">{{ $orderDetail->quantity }}</td>
						<td class="gry-color">{{ single_price($orderDetail->price/$orderDetail->quantity) }}</td>
	                    <td class="text-right">{{ single_price($orderDetail->price+$orderDetail->tax) }}</td>
					</tr>
				@endforeach
            </tbody>
		</table>
	</div>

    <div style="padding:0 1.5rem;">
        <table style="width: 40%;margin-left:auto;" class="text-right sm-padding small strong">
	        <tbody>
		        <tr>
		            <td class="text-left strong">Общая сумма</td>
		            <td>{{ single_price($order->grand_total) }}</td>
		        </tr>
	        </tbody>
	    </table>
    </div>

</div>
