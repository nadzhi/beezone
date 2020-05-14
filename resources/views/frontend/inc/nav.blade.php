<div class="header">
<div class="header-bottom">
<div class="header-bottom-wrapper">
<div class="menu-logo-icon">
<i class="menu-icon-bars las la-bars"></i>
</div>
<div class="header-logo">
<a href="{{ route('home') }}">
@php
$generalsetting = \App\GeneralSetting::first();
@endphp
@if($generalsetting->logo != null)
<img src="{{ asset($generalsetting->logo) }}" class="" alt="active shop">
@else
<img src="{{ asset('frontend/images/logo/logo.png') }}" class="" alt="active shop">
@endif
</a>
</div>
<div class="mobile-search">
<div class="mobile-search-button">
<i class="las la-search"></i>
</div>
<div class="mobile-search-box">
<form action="{{ route('search') }}" method="GET">
<i class="las la-search"></i>   
<input type="text" aria-label="Search" id="search" name="q" class="w-100" placeholder="Поиск по товарам" autocomplete="off">
<button type="submit">
Найти
</button>
</form>
</div>
</div>
<div class="header-search">
<form action="{{ route('search') }}" method="GET" style="width: 400px;">
<div class="d-flex position-relative">
<i class="las la-search"></i>   
<input type="text" aria-label="Search" id="search" name="q" class="w-100" placeholder="Поиск по товарам" autocomplete="off">
<div class="typed-search-box d-none">
<div class="search-preloader">
<div class="loader"><div></div><div></div><div></div></div>
</div>
<div class="search-nothing d-none">

</div>
<div id="search-content">
</div>
</div>
</div>
</form>
</div>
<div class="header-links">
@auth
<a href="{{ route('dashboard') }}"  class="header-register-link">{{ Auth::user()->name }}</a>
<a href="{{ route('logout') }}"  class="header-login-link">{{__('Выйти')}}</a>
@else
<a href="{{ route('user.registration') }}" class="header-register-link">Регистрация</a>
<a href="{{ route('user.login') }}" class="header-login-link">Вход</a>
@endauth
<a href="{{ route('cart') }}" class="header-cart-link">
<i class="la la-shopping-bag"></i>
@if(Session::has('cart'))
<span class="badge" id="cart_items_sidenav">{{ count(Session::get('cart'))}}</span>
@else
<span class="badge" id="cart_items_sidenav">0</span>
@endif
</a>
</div>
<div class="clear"></div>
</div>
</div>

<div class="sticky-element">
<div class="sticky-anchor"></div>
<div class="sticky-content">        
<section class="sticky">
<div class="header-menu">
<div class="menu-wrapper">
<a class="all-products">
<i class="la la-bars"></i>
Все категории
</a>
@foreach (\App\Category::all()->where('featured', '1') as $key => $category)
@if($category->status == 1)
<a @if($category->disabled == 1) class="disabled" @endif href="{{ route('products.category', $category->slug) }}">
{{ __($category->name) }}
</a>
@endif
@endforeach
</div>
</div>
<div class="navbar-menu">
<div class="navbar-menu-wrapper">
<div class="row">
@foreach (\App\Category::all() as $key => $category)
@php
$brands = array();
@endphp
@if($category->status == 1)
<div class="col-3">
<a @if($category->disabled == 1) class="disabled" @endif href="{{ route('products.category', $category->slug) }}">
<img class="cat-image" src="https://test.beezone.kz/public/{{ $category->icon }}" width="30">
<span class="cat-name">{{ __($category->name) }}</span>
</a>
</div>
@endif
@endforeach
</div>
</div>
</div>    
</section>                        
</div>        
</div>

</div>
<div class="mobile-navbar-menu">
<div class="menu-close-icon">
<i class="la la-times"></i>
</div>
<h3>Все категории</h3>
@foreach (\App\Category::all()->where('featured', '1') as $key => $category)
@if($category->status == 1)
<a @if($category->disabled == 1) class="disabled" @endif href="{{ route('products.category', $category->slug) }}">
<img class="cat-image" src="https://test.beezone.kz/public/{{ $category->icon }}" width="20">
<span class="cat-name">{{ __($category->name) }}</span>
</a>
@endif
@endforeach
</div>