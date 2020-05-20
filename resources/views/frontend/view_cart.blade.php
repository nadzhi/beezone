@extends('frontend.layouts.app')

@section('content')
    <section class="slice-xs sct-color-2 border-bottom">
        <div class="container container-sm">
            <div class="row cols-delimited">
                <div class="col-4">
                    <div class="icon-block icon-block--style-1-v5 text-center active">
                        <div class="block-icon mb-0">
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
                    <div class="icon-block icon-block--style-1-v5 text-center">
                        <div class="block-icon c-gray-light mb-0">
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


    <section class="py-4 cart-wrapper" id="cart-summary">
        @include('frontend.partials.cart_details')
    </section>


    <!-- Modal -->
    <div class="modal fade" id="GuestCheckout" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-zoom" role="document">
            <div class="modal-content" style="max-width: 500px; margin:0 auto;">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{__('Login')}}</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="card">
                                <div class="card-body px-4">
                                    <form class="form-default" role="form" action="{{ route('cart.login.submit') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div class="input-group input-group--style-1">
                                                        <input type="email" name="email" class="form-control" placeholder="{{__('Email')}}">
                                                        <span class="input-group-addon">
														<i class="text-md ion-person"></i>
													</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div class="input-group input-group--style-1">
                                                        <input type="password" name="password" class="form-control" placeholder="{{__('Password')}}">
                                                        <span class="input-group-addon">
														<i class="text-md ion-locked"></i>
													</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <a href="#" class="link link-xs link--style-3">{{__('Forgot password?')}}</a>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <button type="submit" class="btn btn-styled btn-base-1 px-4">{{__('Sign in')}}</button>
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
@endsection

@section('script')
    <?php
    $prices = [];
    $gifts = [];
    if(Session::has('cart')):
        foreach(Session::get('cart') as $key => $cart_item){
            $product_prices = \App\ProductPrice::where('product_id', $cart_item['id'])->get();
            if(isset($product_prices)){
                foreach($product_prices as $product_price){
                    if(Auth::check()){
                        $product = \App\Product::where("id",$product_price->product_id)->select("subbrand_id")->first();
                        $supplier_level = \App\SupplierLevel::where([["user_id","=", Auth::user()->id],["brand_id","=", $product->subbrand_id]])->first();

                        if(isset($supplier_level)){
                            $level = $supplier_level->level;
                            $level_product_price = \App\ProductPrice::where('product_id', $product_price->product_id)->orderBy('level','desc')->first();
                            if($product_price->level >= $supplier_level->level){
                                $level = $product_price->level;
                            }
                            if($level >= $level_product_price->level){
                                $level = $level_product_price->level;
                            }
                            $user_product_price = \App\ProductPrice::where([['product_id', $product_price->product_id],['level', $level]])->first();
                            if(isset($user_product_price)){
                                $prices[$key][$product_price->count] = $user_product_price->price;
                            }
                            else{
                                $prices[$key][$product_price->count] = $product_price->price;
                            }
                        }
                        else{
                            $prices[$key][$product_price->count] = $product_price->price;
                        }
                    }
                    else{
                        $prices[$key][$product_price->count] = $product_price->price;
                    }

                }
            }

            if(isset($cart_item["gift"]) AND $cart_item["gift"] == true){
                $gifts[$key] = $cart_item["gift"];
            }
        }
    endif;
    ?>
    <script type="text/javascript">

        let prices = <?php echo json_encode($prices); ?>;
        let gifts = <?php echo json_encode($gifts); ?>;
        let cart_data = [];


        function removeFromCartView(e, key){
            e.preventDefault();
            removeFromCart(key);
        }
        function updateQuantity(key, element){
            $.post('{{ route('cart.updateQuantity') }}', { _token:'{{ csrf_token() }}', key:key, quantity: element.value}, function(data){
                updateNavCart();
                $('#cart-summary').html(data);
            });
        }
        function showCheckoutModal(){
            $('#GuestCheckout').modal();
        }

        $(".go_to_shipping_info").on("click",function(e){
            e.preventDefault();
            let url = $(this).attr("href");

            $(".cart-summary").each(function(index, item){
                let key = $(this).attr("data-key");
                let quantity = $(this).find(".product-name strong").attr("data-value");
                let price = $(this).find(".product-total span").attr("data-value");
                $.post('{{ route('cart.updateQuantity') }}', { _token:'{{ csrf_token() }}', key:key, quantity: quantity,price: price}, function(data){
                    window.location.href = url;
                });
            });

        });

        function update_cart_item(key,quantity,price){
            $.post('{{ route('cart.updateQuantity') }}', { _token:'{{ csrf_token() }}', key:key, quantity: quantity,price: price}, function(data){});
        }

        function cart_sum(){
            let total_sum = 0;
            $(".cart-summary").each(function(index, item){
                let price = $(this).find(".product-total span").attr("data-value");
                let quantity = $(this).find(".product-name strong").attr("data-value");
                total_sum += (price*quantity);
            });

            $(".total_sum").html(formatNumber(total_sum) + "₸");
        }

        $(".input-number").on("change",function(){
            let change_count = parseInt($(this).val());
            let fieldName = $(this).attr('name');
            let input_price = $(this).parents('.cart-item').find(".product-price span");
            let price = parseInt(input_price.attr("data-price"));

            let data_key = 0;
            prices.forEach(function(item, i, arr) {
                if(fieldName == ("quantity["+i+"]")){
                    data_key = i;

                    let last_key = Object.keys(item)[Object.keys(item).length - 1];
                    let last_value = item[Object.keys(item)[Object.keys(item).length - 1]];
                    let k = 0;
                    $.each(item, function(index, value) {
                        k++;
                        let currentKey = Object.keys(item)[k];
                        if(currentKey >= change_count){
                            input_price.html(formatNumber(value) + "₸/шт");
                            input_price.attr("data-price",value);
                            price = value;
                            return false;
                        }
                        if(change_count >= last_key){
                            input_price.html(formatNumber(last_value) + "₸/шт");
                            input_price.attr("data-price",last_value);
                            price = last_value;
                            return false;
                        }
                    });
                }
            });
            let cart_summary = $(".cart-summary-"+data_key);
            cart_summary.find(".product-name strong").html("× "+ change_count);
            cart_summary.find(".product-total span").html(formatNumber((change_count) * price) + "₸");
            cart_summary.find(".product-name strong").attr("data-value",change_count);
            cart_summary.find(".product-total span").attr("data-value",price);
            $(this).parents('.cart-item').find(".product-total span").html(formatNumber((change_count) * price) + "₸");
            cart_sum();
            update_cart_item(data_key,change_count,price);
            gift_element(data_key,change_count);
        });

        function gift_element(data_key,change_count){

            if (gifts[data_key] !== undefined) {
                let gift_count = gifts[data_key].count;
                if(change_count >= gift_count){
                    let counter = parseInt(change_count/gift_count);
                    let product_counter = parseInt(gifts[data_key].product_count);

                    $(".gift-item-" + data_key).html(
                        '<td class="product-image"><a href="#" class="mr-3"><img src="https://test.beezone.kz/public/'+gifts[data_key].image+'"></a></td><td class="product-name" style="width:200px;"><span class="pr-4 d-block">'+gifts[data_key].name+'</span></td><td>'+(counter*product_counter)+' в подарок</td><td></td><td></td><td></td><td class="clear"></td>'
                    );
                }
                else{
                    $(".gift-item-" + data_key).html(null);
                }

            }

        }

        $('.cart-item').on("click",".btn-number", function(e) {
            e.preventDefault();

            let input_price = $(this).parents('.cart-item').find(".product-price span");
            let price = parseInt(input_price.attr("data-price"));

            let box_count = parseInt($(this).attr('data-count'));

            let type = $(this).attr('data-type');

            let fieldName = $(this).attr('data-field');
            var input = $("input[name='" + fieldName + "']");
            var currentVal = parseInt(input.val());


            if (!isNaN(currentVal)) {
                if (type == 'minus') {
                    if(currentVal > box_count){
                        let change_count = currentVal - box_count;
                        let data_key = 0;
                        prices.forEach(function(item, i, arr) {
                            if(fieldName == ("quantity["+i+"]")){
                                data_key = i;
                                let last_key = Object.keys(item)[Object.keys(item).length - 1];
                                let last_value = item[Object.keys(item)[Object.keys(item).length - 1]];
                                let k = 0;
                                $.each(item, function(index, value) {
                                    k++;
                                    let currentKey = Object.keys(item)[k];

                                    if(currentKey > change_count){
                                        input_price.html(formatNumber(value) + "₸/шт");
                                        input_price.attr("data-price",value);
                                        price = value;
                                        return false;
                                    }

                                    if(change_count >= last_key){
                                        input_price.html(formatNumber(last_value) + "₸/шт");
                                        input_price.attr("data-price",last_value);
                                        price = last_value;
                                        return false;
                                    }
                                });
                            }
                        });

                        let cart_summary = $(".cart-summary-"+data_key);
                        cart_summary.find(".product-name strong").html("× "+ change_count);
                        cart_summary.find(".product-total span").html(formatNumber((change_count) * price) + "₸");
                        cart_summary.find(".product-name strong").attr("data-value",change_count);
                        cart_summary.find(".product-total span").attr("data-value",price);
                        input.val(currentVal - box_count);
                        $(this).parents('.cart-item').find(".product-total span").html(formatNumber((change_count) * price) + "₸");
                        cart_sum();
                        update_cart_item(data_key,change_count,price);
                        gift_element(data_key,change_count);
                    }
                }else if (type == 'plus') {
                    let change_count = currentVal + box_count;
                    let data_key = 0;
                    prices.forEach(function(item, i, arr) {
                        if(fieldName == ("quantity["+i+"]")){
                            data_key = i;
                            let last_key = Object.keys(item)[Object.keys(item).length - 1];
                            let last_value = item[Object.keys(item)[Object.keys(item).length - 1]];
                            let k = 0;
                            $.each(item, function(index, value) {
                                k++;
                                let currentKey = Object.keys(item)[k];

                                if(currentKey > change_count){
                                    input_price.html(formatNumber(value) + "₸/шт");
                                    input_price.attr("data-price",value);
                                    price = value;
                                    return false;
                                }
                                if(change_count >= last_key){
                                    input_price.html(formatNumber(last_value) + "₸/шт");
                                    input_price.attr("data-price",last_value);
                                    price = last_value;
                                    return false;
                                }
                            });
                        }
                    });
                    let cart_summary = $(".cart-summary-"+data_key);
                    cart_summary.find(".product-name strong").html("× "+ change_count);
                    cart_summary.find(".product-total span").html(formatNumber((change_count) * price) + "₸");
                    cart_summary.find(".product-name strong").attr("data-value",change_count);
                    cart_summary.find(".product-total span").attr("data-value",price);
                    input.val(currentVal + box_count);
                    $(this).parents('.cart-item').find(".product-total span").html(formatNumber((currentVal + box_count) * price) + "₸");
                    cart_sum();
                    update_cart_item(data_key,change_count,price);
                    gift_element(data_key,change_count);
                }
            }
        });

        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1&nbsp;')
        }

    </script>
@endsection
