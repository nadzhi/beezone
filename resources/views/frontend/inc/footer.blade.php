<div class="mobile-bottom-menu">
    <a href="{{ route('home') }}"><i class="la la-home"></i></a>
    <a href="{{ route('products')  }}"><i class="la la-list-ul"></i></a>
    <a href="{{ route('cart') }}" class="mobile-cart">
        <i class="la la-shopping-bag"></i>
        @if(Session::has('cart'))
            <span class="badge" id="cart_items_sidenav">{{ count(Session::get('cart'))}}</span>
        @else
            <span class="badge" id="cart_items_sidenav">0</span>
        @endif
    </a>
    @auth
        <a href="{{ route('dashboard') }}"><i class="la la-user"></i></a>
    @else
        <a href="{{ route('user.login') }}"><i class="la la-user"></i></a>
    @endauth
    <a class="mobile-info-btn"><i class="la la-ellipsis-h"></i></a>

    <div class="mobile-info">
        <p><b>Онлайн поддержка 24/7</b></p>
        <p><b><i class="la la-mobile-alt"></i><span>4044</span><br/><br/>Бесплатно с мобильного</b></p>
        <a href="tel:+7(777)666-55-44">Call-center: +7 (747) 444-40-44</a>
    </div>

</div>
<div class="search-black-layer"></div>
<div class="city-black-layer"></div>
<footer>
    <div class="footer-wrapper">

        <div class="footer-app">
            <a href="" class="apple"><i class="lab la-apple"></i></a>
            <a href="" class="android"><i class="lab la-google-play"></i></a>
            <a href="https://www.instagram.com/beezone.kz/" id="instagram_app" class="instagram"><i class="lab la-instagram"></i></a>
        </div>

        <ul class="footer-list">
            <!--li><a href="{{ route('sellerpolicy') }}">{{__('Seller Policy')}}</a></li-->
            <!--li><a href="{{ route('returnpolicy') }}">{{__('Return Policy')}}</a></li-->
            <!--li><a href="{{ route('supportpolicy') }}">{{__('Support Policy')}}</a></li-->
            <li><a href="{{ route('faq') }}">Вопросы - Ответы</a></li>
			<!--li><a href="{{ route('shops.create') }}">Стать продавцом</a></li-->
        </ul>


        <p class="footer-copyright">© {{ date('Y') }} Beezone.kz</p>
        <div class="clear"></div>
    </div>
</footer>
