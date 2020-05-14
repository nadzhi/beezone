@extends('frontend.layouts.app')

@section('content')
<div class="slider">
<div class="owl-carousel owl-theme" id="slider">
@foreach (\App\Banner::where('position', 1)->where('published', 1)->get() as $key => $banner)
<div class="item">
<a href="{{ $banner->url }}">
<img src="{{ asset($banner->photo) }}" alt="" />
</a>
</div>
@endforeach
</div>
</div>

<section class="mb-4 product-wrapper px-3 brands-box">
<div class="container products">
<div class="row">
@foreach (\App\Brand::all() as $key => $product)
<div class="col-6" style="padding: 0">
<div class="product-card-2 card card-product m-2 shop-cards">
<div class="card-body p-0">
<div class="card-image">
<a data-id="{{ $product->id }}" href="{{ route('products.brand', $product->slug) }}" class="d-block" 
style="background-image:url('https://test.beezone.kz/public/{{ $product->logo }}');">
</a>
</div>
<div class="p-3">
<h2 class="product-title p-0 text-truncate-2">
<a data-id="{{ $product->id }}" href="{{ route('products.brand', $product->slug) }}">{{ __($product->name) }}</a>
</h2>
</div>
</div>
</div>
</div>
@endforeach
</div>
</div>
</section>
@endsection