<!DOCTYPE html>
@if(\App\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
    <html dir="rtl">
@else
    <html>
@endif
<head>
@php
    $seosetting = \App\SeoSetting::first();
@endphp

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="index, follow">
<meta name="description" content="@yield('meta_description', $seosetting->description)" />
<meta name="keywords" content="@yield('meta_keywords', $seosetting->keyword)">
<meta name="author" content="{{ $seosetting->author }}">
<meta name="sitemap_link" content="{{ $seosetting->sitemap_link }}">
@yield('meta')

<!-- Schema.org markup for Google+ -->
<meta itemprop="name" content="{{ config('app.name', 'Laravel') }}">
<meta itemprop="description" content="{{ $seosetting->description }}">
<meta itemprop="image" content="{{ asset(\App\GeneralSetting::first()->logo) }}">

<!-- Twitter Card data -->
<meta name="twitter:card" content="product">
<meta name="twitter:site" content="@publisher_handle">
<meta name="twitter:title" content="{{ config('app.name', 'Laravel') }}">
<meta name="twitter:description" content="{{ $seosetting->description }}">
<meta name="twitter:creator" content="@author_handle">
<meta name="twitter:image" content="{{ asset(\App\GeneralSetting::first()->logo) }}">

<!-- Open Graph data -->
<meta property="og:title" content="{{ config('app.name', 'Laravel') }}" />
<meta property="og:type" content="Ecommerce Site" />
<meta property="og:url" content="{{ route('home') }}" />
<meta property="og:image" content="{{ asset(\App\GeneralSetting::first()->logo) }}" />
<meta property="og:description" content="{{ $seosetting->description }}" />
<meta property="og:site_name" content="{{ env('APP_NAME') }}" />

<!-- Favicon -->
<link name="favicon" type="image/x-icon" href="{{ asset(\App\GeneralSetting::first()->favicon) }}" rel="shortcut icon" />

<title>@yield('meta_title', config('app.name', 'Laravel'))</title>

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap" rel="stylesheet">

<!-- Bootstrap -->
<link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}" type="text/css">

<!-- Icons -->
<link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('frontend/css/line-awesome.min.css') }}" type="text/css">

<link type="text/css" href="{{ asset('frontend/css/bootstrap-tagsinput.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('frontend/css/jodit.min.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('frontend/css/sweetalert2.min.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('frontend/css/slick.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('frontend/css/xzoom.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('frontend/css/jquery.share.css') }}" rel="stylesheet">

<!-- Global style (main) -->
<link type="text/css" href="{{ asset('frontend/css/active-shop.css') }}" rel="stylesheet" media="screen">

<!--Spectrum Stylesheet [ REQUIRED ]-->
<link href="{{ asset('css/spectrum.css')}}" rel="stylesheet">

<!-- Custom style -->
<link type="text/css" href="{{ asset('frontend/css/custom-style.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('frontend/css/style.css')}}" rel="stylesheet">

@if(\App\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
     <!-- RTL -->
    <link type="text/css" href="{{ asset('frontend/css/active.rtl.css') }}" rel="stylesheet">
@endif


    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">

<!-- Facebook Chat style -->
<link href="{{ asset('frontend/css/fb-style.css')}}" rel="stylesheet">
<link href="{{ asset('frontend/css/owl.carousel.css')}}" rel="stylesheet">

<!-- color theme -->
<link href="{{ asset('frontend/css/colors/'.\App\GeneralSetting::first()->frontend_color.'.css')}}" rel="stylesheet">

<!-- jQuery -->
<script src="{{ asset('frontend/js/vendor/jquery.min.js') }}"></script>
<script src="{{ asset('frontend/js/vendor/swipe.js') }}"></script>

@if (\App\BusinessSetting::where('type', 'google_analytics')->first()->value == 1)
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-133955404-1"></script>

    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', @php env('TRACKING_ID') @endphp);
    </script>
@endif

</head>
<body>
<div class="loaderArea" id="loaderArea">
<div class="loader-flex" id="loader">
<div class="loader-item-1"></div>
<div class="loader-item-2"></div>
<div class="loader-item-3"></div>
<div class="loader-item-4"></div>
<div class="loader-item-5"></div>
</div>
</div>

<!-- MAIN WRAPPER -->
<div class="body-wrap shop-default shop-cards shop-tech gry-bg">

    <!-- Header -->
    @include('frontend.inc.nav')

    @yield('content')

    @include('frontend.inc.footer')

    @include('frontend.partials.modal')

    <div class="modal fade" id="addToCart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="c-preloader">
                    <i class="fa fa-spin fa-spinner"></i>
                </div>
                <button type="button" class="close absolute-close-btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div id="addToCart-modal-body">

                </div>
            </div>
        </div>
    </div>

    @if (\App\BusinessSetting::where('type', 'facebook_chat')->first()->value == 1)
        <div id="fb-root"></div>
        <!-- Your customer chat code -->
        <div class="fb-customerchat"
          attribution=setup_tool
          page_id="{{ env('FACEBOOK_PAGE_ID') }}">
        </div>
    @endif

</div><!-- END: body-wrap -->

<!-- SCRIPTS -->
<!--a href="#" class="back-to-top btn-back-to-top"></a-->
	<script  type="text/javascript">
window.onload = function(){
	let preloader = document.getElementById("loaderArea");
	let loader = document.getElementById("loader");
	let opacity = 1;
	let timer = setInterval(function() {
		if(opacity <= 0.1) {
			clearInterval(timer);
			preloader.style.display = "none";
		}
		preloader.style.opacity = opacity;
		opacity -= opacity * 0.1;
		document.body.style.overflow = "auto";
	}, 5);
}
</script>

<!-- Core -->
<script src="{{ asset('frontend/js/vendor/popper.min.js') }}"></script>
<script src="{{ asset('frontend/js/vendor/bootstrap.min.js') }}"></script>

<!-- Plugins: Sorted A-Z -->
<script src="{{ asset('frontend/js/jquery.countdown.min.js') }}"></script>
<script src="{{ asset('frontend/js/select2.min.js') }}"></script>
<script src="{{ asset('frontend/js/nouislider.min.js') }}"></script>


<script src="{{ asset('frontend/js/sweetalert2.min.js') }}"></script>
<script src="{{ asset('frontend/js/slick.min.js') }}"></script>

<script src="{{ asset('frontend/js/jquery.share.js') }}"></script>
<script src="{{ asset('frontend/js/owl.carousel.js') }}"></script>

<script src="{{ asset('frontend/js/jquery.maskedinput.js') }}"></script>

<script type="text/javascript">
    function showFrontendAlert(type, message){
        if(type == 'danger'){
            type = 'error';
        }
        swal({
            position: 'top-end',
            type: type,
            title: message,
            showConfirmButton: false,
            timer: 1500
        });
    }
	 function showMobileAlert(type, message){
        if(type == 'danger'){
            type = 'error';
        }
        swal({
            type: type,
            title: message,
            showConfirmButton: false,
            timer: 1500
        });
    }
	function showLevel(type, message){
		swal({
			type: type,
			title: message,
			showConfirmButton: false,
			timer: 1500
		});
    }
</script>

@foreach (session('flash_notification', collect())->toArray() as $message)
    <script type="text/javascript">
		if(screen.width >= 700){
			showFrontendAlert('{{ $message['level'] }}', '{{ $message['message'] }}');
		}
        else{
			showMobileAlert('{{ $message['level'] }}', '{{ $message['message'] }}');
		}
    </script>
@endforeach

@if(Session::has('new_level'))
    <script type="text/javascript">
		setTimeout(
        	showLevel('success', '{{ Session::get('new_level') }}'),
			2000
		);
    </script>
	@php Session::forget("new_level") @endphp
@endif

<script>

    $(document).ready(function() {
        if ($('#lang-change').length > 0) {
            $('#lang-change .dropdown-item a').each(function() {
                $(this).on('click', function(e){
                    e.preventDefault();
                    var $this = $(this);
                    var locale = $this.data('flag');
                    $.post('{{ route('language.change') }}',{_token:'{{ csrf_token() }}', locale:locale}, function(data){
                        location.reload();
                    });

                });
            });
        }
        if ($('#currency-change').length > 0) {
            $('#currency-change .dropdown-item a').each(function() {
                $(this).on('click', function(e){
                    e.preventDefault();
                    var $this = $(this);
                    var currency_code = $this.data('currency');
                    $.post('{{ route('currency.change') }}',{_token:'{{ csrf_token() }}', currency_code:currency_code}, function(data){
                        location.reload();
                    });

                });
            });
        }
    });

    $('#search').on('keyup', function(){
        search();
    });

    $('#search').on('focus', function(){
        search();
    });

    function search(){
        var search = $('#search').val();
        if(search.length > 0){
            $('body').addClass("typed-search-box-shown");

            $('.typed-search-box').removeClass('d-none');
            $('.search-preloader').removeClass('d-none');
            $.post('{{ route('search.ajax') }}', { _token: '{{ @csrf_token() }}', search:search}, function(data){
                if(data == '0'){
                    // $('.typed-search-box').addClass('d-none');
                    $('#search-content').html(null);
                    $('.typed-search-box .search-nothing').removeClass('d-none').html('Sorry, nothing found for <strong>"'+search+'"</strong>');
                    $('.search-preloader').addClass('d-none');

                }
                else{
                    $('.typed-search-box .search-nothing').addClass('d-none').html(null);
                    $('#search-content').html(data);
                    $('.search-preloader').addClass('d-none');
                }
            });
        }
        else {
            $('.typed-search-box').addClass('d-none');
            $('body').removeClass("typed-search-box-shown");
        }
    }

    function updateNavCart(){
        $.post('{{ route('cart.nav_cart') }}', {_token:'{{ csrf_token() }}'}, function(data){
            $('#cart_items').html(data);
        });
    }

    function removeFromCart(key){
        $.post('{{ route('cart.removeFromCart') }}', {_token:'{{ csrf_token() }}', key:key}, function(data){
            updateNavCart();
            $('#cart-summary').html(data);
            showFrontendAlert('success', 'Товар был удален из корзины');
            location.reload();
        });
    }

    function showAddToCartModal(id){
        if(!$('#modal-size').hasClass('modal-lg')){
            $('#modal-size').addClass('modal-lg');
        }
        $('#addToCart-modal-body').html(null);
        $('#addToCart').modal();
        $('.c-preloader').show();
        $.post('{{ route('cart.showCartModal') }}', {_token:'{{ csrf_token() }}', id:id}, function(data){
            $('.c-preloader').hide();
            $('#addToCart-modal-body').html(data);
            $('.xzoom, .xzoom-gallery').xzoom({
                Xoffset: 20,
                bg: true,
                tint: '#000',
                defaultScale: -1
            });
            getVariantPrice();
        });
    }

    $('#option-choice-form input').on('change', function(){
        getVariantPrice();
    });

    function getVariantPrice(){
        if($('#option-choice-form input[name=quantity]').val() > 0 && checkAddToCartValidity()){
            $.ajax({
               type:"POST",
               url: '{{ route('products.variant_price') }}',
               data: $('#option-choice-form').serializeArray(),
               success: function(data){
                   $('#option-choice-form #chosen_price_div').removeClass('d-none');
                   $('#option-choice-form #chosen_price_div #chosen_price').html(data.price);
                   $('#available-quantity').html(data.quantity);
               }
           });
        }
    }

    function checkAddToCartValidity(){
        var names = {};
        $('#option-choice-form input:radio').each(function() { // find unique names
              names[$(this).attr('name')] = true;
        });
        var count = 0;
        $.each(names, function() { // then count them
              count++;
        });
        if($('input:radio:checked').length == count){
            return true;
        }
        return false;
    }

    function addToCart(){
        $('#addToCart').modal();
        $('.c-preloader').show();
        $.ajax({
           type:"POST",
           url: '{{ route('cart.addToCart') }}',
           data: $('#option-choice-form').serializeArray(),
           success: function(data){
               $('#addToCart-modal-body').html(null);
               $('.c-preloader').hide();
               $('#modal-size').removeClass('modal-lg');
               $('#addToCart-modal-body').html(data);
               updateNavCart();
               $('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html())+1);
           }
       });
    }

    function addSelect(val){
          $('#addToCart').modal();
          $('.c-preloader').show();
          $.ajax({
             type:"POST",
             url: '{{ route('cart.addToCart') }}',
             data: $('#option-choice-form-'+val).serializeArray(),
             success: function(data){
                 $('#addToCart-modal-body').html(null);
                 $('.c-preloader').hide();
                 $('#modal-size').removeClass('modal-lg');
                 $('#addToCart-modal-body').html(data);
                 updateNavCart();
                 $('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html())+1);
			  	 if($(window).width() <= 700){
			  		$("#price-matrix").animate({'right': "-330px"});
					$(".product-box-2").css("border","");
					$(this).parent().parent().css("border","");
					$(".product-box-2").css("boxShadow","");
					$(this).parent().parent().css("boxShadow","");
				 }
             }
         });
    }

    function buyNow(){
        if(checkAddToCartValidity()) {
            $('#addToCart').modal();
            $('.c-preloader').show();
            $.ajax({
               type:"POST",
               url: '{{ route('cart.addToCart') }}',
               data: $('#option-choice-form').serializeArray(),
               success: function(data){
                   //$('#addToCart-modal-body').html(null);
                   //$('.c-preloader').hide();
                   //$('#modal-size').removeClass('modal-lg');
                   //$('#addToCart-modal-body').html(data);
                   updateNavCart();
                   $('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html())+1);
                   window.location.replace("{{ route('checkout.shipping_info') }}");
               }
           });
        }
        else{
            showFrontendAlert('warning', 'Пожалуйста, выберите все варианты');
        }
    }

	function buyNowSeller(id){
		$('#addToCart').modal();
		$('.c-preloader').show();
		$.ajax({
			type:"POST",
			url: '{{ route('cart.addToCart') }}',
			data: $('.option-choice-form-seller-'+id).serializeArray(),
			success: function(data){
				$('#addToCart-modal-body').html(null);
				$('.c-preloader').hide();
				$('#modal-size').removeClass('modal-lg');
				$('#addToCart-modal-body').html(data);
				updateNavCart();
				$('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html())+1);
			}
		});
	}

    function show_purchase_history_details(order_id)
    {
        $('#order-details-modal-body').html(null);

        if(!$('#modal-size').hasClass('modal-lg')){
            $('#modal-size').addClass('modal-lg');
        }

        $.post('{{ route('purchase_history.details') }}', { _token : '{{ @csrf_token() }}', order_id : order_id}, function(data){
            $('#order-details-modal-body').html(data);
            $('#order_details').modal();
            $('.c-preloader').hide();
        });
    }

	function show_diller_detail(user_id)
    {
        $('#dillers-info-modal-body').html(null);

        if(!$('#modal-size').hasClass('modal-lg')){
            $('#modal-size').addClass('modal-lg');
        }

        $.post('{{ route('diller.info') }}', { _token : '{{ @csrf_token() }}', user_id : user_id}, function(data){
            $('#dillers-info-modal-body').html(data);
            $('#dillers-info').modal();
            $('.c-preloader').hide();
        });
    }

    function show_order_details(order_id)
    {
        $('#order-details-modal-body').html(null);

        if(!$('#modal-size').hasClass('modal-lg')){
            $('#modal-size').addClass('modal-lg');
        }

        $.post('{{ route('orders.details') }}', { _token : '{{ @csrf_token() }}', order_id : order_id}, function(data){
            $('#order-details-modal-body').html(data);
            $('#order_details').modal();
            $('.c-preloader').hide();
        });
    }


     function imageInputInitialize(){
         $('.custom-input-file').each(function() {
             var $input = $(this),
                 $label = $input.next('label'),
                 labelVal = $label.html();

             $input.on('change', function(e) {
                 var fileName = '';

                 if (this.files && this.files.length > 1)
                     fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}', this.files.length);
                 else if (e.target.value)
                     fileName = e.target.value.split('\\').pop();

                 if (fileName)
                     $label.find('span').html(fileName);
                 else
                     $label.html(labelVal);
             });

             // Firefox bug fix
             $input
                 .on('focus', function() {
                     $input.addClass('has-focus');
                 })
                 .on('blur', function() {
                     $input.removeClass('has-focus');
                 });
         });
     }

</script>

<script type="text/javascript">
	$('#slider').owlCarousel({
		loop:true,
		margin:20,
		nav:true,
		autoplay:true,
		autoplayTimeout:3000,
		navText : ['<i class="la la-angle-left"></i>','<i class="la la-angle-right"></i>'],
		responsive:{
			0:{
				items:1
			},
			600:{
				items:2
			},
		}
	});
</script>
<script src="{{ asset('frontend/js/bootstrap-tagsinput.min.js') }}"></script>
<script src="{{ asset('frontend/js/jodit.min.js') }}"></script>
<script src="{{ asset('frontend/js/xzoom.min.js') }}"></script>
<script src="{{ asset('frontend/js/active-shop.js') }}"></script>
<script src="{{ asset('frontend/js/main.js') }}"></script>
<script src="{{ asset('frontend/js/fb-script.js') }}"></script>
@yield('script')
</body>
</html>
