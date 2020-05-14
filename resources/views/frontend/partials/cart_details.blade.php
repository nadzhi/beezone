@if(Session::has('cart') AND count(Session::get('cart')) > 0)
<div class="container">
    <div class="row cols-xs-space cols-sm-space cols-md-space">
        <div class="col-xl-8" >
            <div class="form-default bg-white p-4">
                <div class="">
                    <div class="" style="overflow: auto">
                        <table class="table-cart border-bottom table-cart-mob">
                            <thead>
                                <tr>
                                    <th class="product-image"></th>
                                    <th class="product-name">{{__('Product')}}</th>
                                    <th class="product-price d-none d-lg-table-cell">{{__('Price')}}</th>
                                    <th class="product-quanity d-none d-md-table-cell">{{__('Quantity')}}</th>
                                    <th class="product-total">Итого</th>
                                    <th class="product-remove"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $total = 0;
                                @endphp
								@if(Session::has('cart'))
                                @foreach (Session::get('cart') as $key => $cartItem)
                                    @php
                                    $product = \App\Product::find($cartItem['id']);
                                    $total = $total + $cartItem['price']*$cartItem['quantity'];
                                    $product_name_with_choice = $product->name;
                                    @endphp
                                    <tr class="cart-item">
                                        <td class="product-image">
											<a href="#" class="mr-3">
												<img src="https://test.beezone.kz/public/{{ $product->thumbnail_img }}">
											</a>
										</td>

                                        <td class="product-name"  style="width:200px;">
                                            <span class="pr-4 d-block">{{ $product_name_with_choice }}<br/>
												<span style="margin-top:-3px;font-size:13px;font-weight:normal;color:#999">(Количество в коробке - {{ $cartItem['box_count'] }})</span>
											</span>
                                        </td>

                                        <td class="product-price d-none d-lg-table-cell">
                                            <span class="pr-3 d-block" data-price="{{ $cartItem['price'] }}">{{ single_price($cartItem['price']) }}/шт</span>
                                        </td>

                                        <td class="product-quantity d-none d-md-table-cell">
                                            <div class="input-group input-group--style-2 pr-4" style="width: 130px;">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-number"  data-count="{{ $cartItem['box_count'] }}" type="button" data-type="minus" data-id="{{ $key }}" data-field="quantity[{{ $key }}]">
                                                        <i class="la la-minus"></i>
                                                    </button>
                                                </span>
                                                <input style="width: 30px;padding: 0;text-align: center;" 
													   type="text" 
													   name="quantity[{{ $key }}]" 
													   class="form-control input-number" 
													   value="{{ $cartItem['quantity'] }}"  max="10000000" min="1" >
                                                <span class="input-group-btn">
                                                    <button class="btn btn-number"  data-count="{{ $cartItem['box_count'] }}" type="button" data-type="plus" data-id="{{ $key }}" data-field="quantity[{{ $key }}]">
                                                        <i class="la la-plus"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </td>




                                        <td class="product-total">
											<small style="font-size:13px;font-weight:normal;color:#999">Итого:</small> <br/><span>{{ single_price($cartItem['price']*$cartItem['quantity']) }}</span>
                                        </td>
                                        <td class="product-remove">
                                            <a href="#" onclick="removeFromCartView(event, {{ $key }})" class="text-right pl-4">
                                                <i class="la la-trash"></i>
                                            </a>
                                        </td>
										<td class="clear"></td>
                                    </tr>
									<tr class="gift-item-<?php echo $key; ?>">
										<?php if(isset($cartItem["gift"]) AND $cartItem["gift"] == true): ?>
											<?php if($cartItem["gift"]["count"] <= $cartItem["quantity"]): ?>
												<td class="product-image">
													<a href="#" class="mr-3">
														<img src="https://test.beezone.kz/public/<?php echo $cartItem["gift"]["image"]; ?>">
													</a>
												</td>
												<td class="product-name" style="width:200px;">
													<span class="pr-4 d-block"><?php echo $cartItem["gift"]["name"]; ?></span>
												</td>
												<td><?php echo intval($cartItem["quantity"]/$cartItem["gift"]["count"])*$cartItem["gift"]["product_count"]; ?> в подарок</td>
												<td></td><td></td><td class="clear"></td>
											<?php endif; ?>
										<?php endif; ?>
									</tr>
                                @endforeach
								@endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row align-items-center pt-4 cart-buttons">
					<div class="col-12 pb-4 text-right mobile-total" style="font-size: 15px;">
						Сумма к оплате: 
						<span class="total_sum strong-600">{{ single_price($total) }}</span>
					</div>
					<div class="col-6">
						<a href="{{ route('home') }}" class="link link--style-3">
							<i class="la la-mail-reply"></i>
							{{__('Return to shop')}}
						</a>
					</div>
					<div class="col-6 text-right">
						@if(Auth::check())
						<a href="{{ route('checkout.shipping_info') }}" class="btn btn-styled btn-base-1 go_to_shipping_info">{{__('Continue to Shipping')}}</a>
						@else
						<button class="btn btn-styled btn-base-1" onclick="showCheckoutModal()">{{__('Continue to Shipping')}}</button>
						@endif
					</div>
				</div>
            </div>
        </div>

        <div class="col-xl-4 ml-lg-auto">
            @include('frontend.partials.cart_summary')
        </div>
    </div>
</div>
@else
<div class="dc-header">
	<h3 class="heading heading-6 strong-700">{{__('Your Cart is empty')}}</h3>
</div>
@endif