@extends('frontend.layouts.app')

@if(isset($subsubcategory_id))
    @php
        $meta_title = \App\SubSubCategory::find($subsubcategory_id)->meta_title;
        $meta_description = \App\SubSubCategory::find($subsubcategory_id)->meta_description;
    @endphp
@elseif (isset($subcategory_id))
    @php
        $meta_title = \App\SubCategory::find($subcategory_id)->meta_title;
        $meta_description = \App\SubCategory::find($subcategory_id)->meta_description;
    @endphp
@elseif (isset($category_id))
    @php
        $meta_title = \App\Category::find($category_id)->meta_title;
        $meta_description = \App\Category::find($category_id)->meta_description;
    @endphp
@elseif (isset($brand_id))
    @php
        $meta_title = \App\Brand::find($brand_id)->meta_title;
        $meta_description = \App\Brand::find($brand_id)->meta_description;
    @endphp
@else
    @php
        $meta_title = env('APP_NAME');
        $meta_description = \App\SeoSetting::first()->description;
    @endphp
@endif

@section('meta_title'){{ $meta_title }}@stop
@section('meta_description'){{ $meta_description }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $meta_title }}">
    <meta itemprop="description" content="{{ $meta_description }}">

    <!-- Twitter Card data -->
    <meta name="twitter:title" content="{{ $meta_title }}">
    <meta name="twitter:description" content="{{ $meta_description }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $meta_title }}" />
    <meta property="og:description" content="{{ $meta_description }}" />
@endsection

@section('content')
<div class="breadcrumb-area">
<div class="container">
<div class="row wrapper">
<div class="col">
<ul class="breadcrumb">
<li><a href="{{ route('home') }}">{{__('Home')}}</a></li>
<li><a href="{{ route('products') }}">{{__('All Categories')}}</a></li>
@if(isset($category_id))
<li class="active"><a href="{{ route('products.category', \App\Category::find($category_id)->slug) }}">{{ \App\Category::find($category_id)->name }}</a></li>
@endif
@if(isset($subcategory_id))
<li><a href="{{ route('products.category', \App\SubCategory::find($subcategory_id)->category->slug) }}">{{ \App\SubCategory::find($subcategory_id)->category->name }}</a></li>
<li class="active"><a href="{{ route('products.subcategory', \App\SubCategory::find($subcategory_id)->slug) }}">{{ \App\SubCategory::find($subcategory_id)->name }}</a></li>
@endif
@if(isset($subsubcategory_id))
<li ><a href="{{ route('products.category', \App\SubSubCategory::find($subsubcategory_id)->subcategory->category->slug) }}">{{ \App\SubSubCategory::find($subsubcategory_id)->subcategory->category->name }}</a></li>
<li ><a href="{{ route('products.subcategory', \App\SubsubCategory::find($subsubcategory_id)->subcategory->slug) }}">{{ \App\SubsubCategory::find($subsubcategory_id)->subcategory->name }}</a></li>
<li class="active"><a href="{{ route('products.subsubcategory', \App\SubSubCategory::find($subsubcategory_id)->slug) }}">{{ \App\SubSubCategory::find($subsubcategory_id)->name }}</a></li>
@endif
</ul>
</div>
</div>
</div>
</div>

<section class="gry-bg pb-4 wrapper">
<div class="container">
<div class="b-text">
@if(isset($category_id))
{{ \App\Category::find($category_id)->name }}
@elseif(isset($subcategory_id))
{{ \App\SubCategory::find($subcategory_id)->name }}
@elseif(isset($subsubcategory_id))
{{ \App\SubSubCategory::find($subsubcategory_id)->name }}
@else
{{__('All Categories')}}
@endif
</div>
<div class="row">
<div class="col-xl-8 product-listing-box"  style="padding-left:0;margin-bottom:20px;">
<div class="pb-2 bg-white">
@php $brands = array(); @endphp
@if(isset($subsubcategory_id))
	@php
	foreach (json_decode(\App\SubSubCategory::find($subsubcategory_id)->brands) as $brand) {
		if(!in_array($brand, $brands)){
			array_push($brands, $brand);
		}
	}
	@endphp
@elseif(isset($subcategory_id))
	@foreach (\App\SubCategory::find($subcategory_id)->subsubcategories as $key => $subsubcategory)
		@php
			foreach (json_decode($subsubcategory->brands) as $brand) {
				if(!in_array($brand, $brands)){
					array_push($brands, $brand);
				}
			}
		@endphp
	@endforeach
@elseif(isset($category_id))
    @foreach (\App\Category::find($category_id)->subcategories as $key => $subcategory)
		@foreach ($subcategory->subsubcategories as $key => $subsubcategory)
			@php
				foreach (json_decode($subsubcategory->brands) as $brand) {
					if(!in_array($brand, $brands)){
						array_push($brands, $brand);
					}
				}
			@endphp
		@endforeach
	@endforeach
@else
	@php
		foreach (\App\Brand::all() as $key => $brand){
			if(!in_array($brand->id, $brands)){
				array_push($brands, $brand->id);
			}
		}
	@endphp
@endif
</div>
<form class="" id="search-form" action="{{ route('search') }}" method="GET">
@isset($category_id)
<input type="hidden" name="category" value="{{ \App\Category::find($category_id)->slug }}">
@endisset
@isset($subcategory_id)
<input type="hidden" name="subcategory" value="{{ \App\SubCategory::find($subcategory_id)->slug }}">
@endisset
@isset($subsubcategory_id)
<input type="hidden" name="subsubcategory" value="{{ \App\SubSubCategory::find($subsubcategory_id)->slug }}">
@endisset
<div class="sort-by-bar row no-gutters bg-white mb-3 px-3">
<div class="col-lg-4 col-md-5">
<div class="sort-by-box">
<div class="form-group">
<label>{{__('Search')}}</label>
<div class="search-widget">
<input class="form-control input-lg" type="text" name="q" placeholder="{{__('Search products')}}" @isset($query) value="{{ $query }}" @endisset>
<button type="submit" class="btn-inner">
<i class="fa fa-search"></i>
</button>
</div>
</div>
</div>
</div>
<div class="col-md-7 offset-lg-1">
<div class="row no-gutters">
<div class="col-6">
<div class="sort-by-box pr-1">
<div class="form-group">
<label>{{__('Sort by')}}</label>
<select class="form-control sortSelect" data-minimum-results-for-search="Infinity" name="sort_by" onchange="filter()">
<option value="1" @isset($sort_by) @if ($sort_by == '1') selected @endif @endisset>{{__('Newest')}}</option>
<option value="2" @isset($sort_by) @if ($sort_by == '2') selected @endif @endisset>{{__('Oldest')}}</option>
<option value="3" @isset($sort_by) @if ($sort_by == '3') selected @endif @endisset>{{__('Price low to high')}}</option>
<option value="4" @isset($sort_by) @if ($sort_by == '4') selected @endif @endisset>{{__('Price high to low')}}</option>
</select>
</div>
</div>
</div>
<div class="col-6">
<div class="sort-by-box pl-1">
<div class="form-group">
<label>{{__('Brands')}}</label>
<select class="form-control sortSelect" data-placeholder="{{__('All Brands')}}" name="brand" onchange="filter()">
<option value="">{{__('All Brands')}}</option>
@foreach ($brands as $key => $id)
@if (\App\Brand::find($id) != null)
<option value="{{ \App\Brand::find($id)->slug }}" @isset($brand_id) @if ($brand_id == $id) selected @endif @endisset>{{ \App\Brand::find($id)->name }}</option>
@endif
@endforeach
</select>
</div>
</div>
</div>
</div>
</div>
</div>
<input type="hidden" name="min_price" value="">
<input type="hidden" name="max_price" value="">
</form>
<div class="products-box-bar p-3 bg-white">
<div class="row sm-no-gutters gutters-5" id="">
@foreach ($products as $key => $product)
<div class="col-xxl-3 col-xl-4 col-lg-3 col-md-4 col-6 js_event_product" data-id="{{ $product->id }}" >
<div class="product-box-2 bg-white alt-box my-2">
<div class="position-relative overflow-hidden product-links py-2">
<a data-id="{{ $product->id }}" 
href="{{ route('product', $product->slug) }}" 
class="d-block product-image h-100" 
style="background-image:url('https://test.beezone.kz/public/{{ $product->thumbnail_img }}');" 
tabindex="0">
</a>
</div>
<h2 class="product-title p-3 product-links border-top">
<a data-id="{{ $product->id }}" href="{{ route('product', $product->slug) }}" tabindex="0">
{{ __($product->name) }}
</a>
</h2>
</div>
</div>
@endforeach
</div>
</div>
<div class="products-pagination bg-white p-3">
<nav aria-label="Center aligned pagination">
<ul class="pagination justify-content-center">
{{ $products->links() }}
</ul>
</nav>
</div>
</div>
<div class="col-xl-4 category" id="price-matrix">
<div class="bg-white sidebar-box mb-2">
<div class="box-title text-center">
<p style="padding:0 10px;font-weight:bold;font-size:20px;padding-top:15px;">Ценовая матрица</p>
<p class="price-close-symbol">&#10006;</p>
</div>
<div class="box-content">
<div class="range-slider-wrapper mt-3">
<div class="price-matrix">
<div style="text-align:center;padding-top: 130px;opacity:0.3;">
<img src="{{ asset('uploads/images/icon.png') }}" />
</div>
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
	function filter(){
		$('#search-form').submit();
	}
	$(document).ready(function(){
		$(".product-links a,js_event_product").on("click",function(e){
			e.preventDefault();
			var product_id = $(this).attr("data-id");
			$.post("{{ route('product.price') }}", { _token: '{{ @csrf_token() }}', product_id:product_id}, function(data){
				$('.price-matrix').html(data);
				$(".price-matrix").animate({
					height: "show"
				});
			});
			$(".product-box-2").css("border","1px solid #eeeeee");
			$(this).parent().parent().css("border","1px solid #fec200");
			$(".product-box-2").css("boxShadow","0 0 0 #eeeeee inset");
			$(this).parent().parent().css("boxShadow","0 0 3px #fec200 inset");
			if($(window).width() <= 1200){
        		$("#price-matrix").animate({'right': "0px"});
			}
		});
		if($(window).width() <= 1200){
			$(".price-close-symbol").on("click",function(){
				$("#price-matrix").animate({'right': "-330px"});
				$(".product-box-2").css("border","");
				$(this).parent().parent().css("border","");
				$(".product-box-2").css("boxShadow","");
				$(this).parent().parent().css("boxShadow","");
			});
			$("body").on('click', function(e){
				var price_matrix = $("#price-matrix");
				if (!price_matrix.is(e.target) && price_matrix.has(e.target).length === 0 && !$(e.target).parent().hasClass("product-links")) {
					$("#price-matrix").animate({'right': "-330px"});
					$(".product-box-2").css("border","");
					$(this).parent().parent().css("border","");
					$(".product-box-2").css("boxShadow","");
					$(this).parent().parent().css("boxShadow","");
				}
			});
			$("body").swipe({swipeStatus:function(event, phase, direction, distance, duration, fingers){
				if (phase=="move" && direction =="right") {
					$("#price-matrix").animate({'right': "-330px"});
					$(".product-box-2").css("border","");
					$(this).parent().parent().css("border","");
					$(".product-box-2").css("boxShadow","");
					$(this).parent().parent().css("boxShadow","");
					return false;
				}
			}});
		}
		
		$("body").on("click",".product-level-minus-button",function(){
			let data_count = parseInt($(this).closest("table").attr("data-count"));
			let value_count = parseInt($(this).next().html());
			let price_value = parseInt($(this).closest("tr").find(".product-price-value").attr("data-sum"));
			
			
			
			let prices_list = []; 
			$(this).closest("table").find(".product-price-value").each(function(){
				prices_list.push($(this).attr("data-sum"));
			});
			
			let levels_list = [];
			$(this).closest("table").find(".product-count-value").each(function(){
				levels_list.push($(this).attr("data-count"));
			});
			
			if((value_count-data_count) > 0){
				$(this).next().html(value_count-data_count);
				$(this).closest("tr").find("input[name='quantity']").val(value_count-data_count);
				
				let object_price_sum = $(this).closest("tr").find(".product-price-value");
				$.each(levels_list, function(index, value) {
					if(value >= (value_count-data_count)){
						price_value = prices_list[index];
						object_price_sum.html(formatNumber(price_value) + "₸");
						return false;
					}
				}); 
				
				$(this).closest("tr")
					.find(".product-price-total")
					.html(formatNumber((value_count-data_count)*price_value) + "₸");
			}
		});
		
		$("body").on("click",".product-level-plus-button",function(){
			let data_count = parseInt($(this).closest("table").attr("data-count"));
			let value_count = parseInt($(this).prev().html());
			let price_value = parseInt($(this).closest("tr").find(".product-price-value").attr("data-sum"));
			
			let prices_list = []; 
			$(this).closest("table").find(".product-price-value").each(function(){
				prices_list.push($(this).attr("data-sum"));
			});
			
			let levels_list = [];
			$(this).closest("table").find(".product-count-value").each(function(){
				levels_list.push($(this).attr("data-count"));
			});
			
			
			$(this).prev().html(value_count+data_count);
			$(this).closest("tr").find("input[name='quantity']").val(value_count+data_count);
			
			let object_price_sum = $(this).closest("tr").find(".product-price-value");
			$.each(levels_list, function(index, value) {
				if(value >= (value_count+data_count)){
					price_value = prices_list[index];
					object_price_sum.html(formatNumber(price_value) + "₸");
					return false;
				}
			}); 

			$(this).closest("tr")
					.find(".product-price-total")
					.html(formatNumber((value_count+data_count)*price_value) + "₸")
		});
		
		function formatNumber(num) {
			return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1&nbsp;')
		}

	});
	
</script>
@endsection


