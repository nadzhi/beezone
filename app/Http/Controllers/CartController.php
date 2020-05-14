<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\ProductPrice;
use App\SubSubCategory;
use App\Category;
use App\SupplierLevel;
use Session;
use Auth;
use App\Color;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index(Request $request)
    {
        //dd($cart->all());
        $categories = Category::all();
        return view('frontend.view_cart', compact('categories'));
    }

    public function showCartModal(Request $request)
    {
        $product = Product::find($request->id);
        return view('frontend.partials.addToCart', compact('product'));
    }

    public function updateNavCart(Request $request)
    {
        return view('frontend.partials.cart');
    }

    public function addToCart(Request $request)
    {
        $product = Product::find($request->id);
        $product_price = DB::table("product_prices")->where([['product_id', "=" , $request->id],[ 'level', "=" , $request->level]])->first();
		
		$gift_product = false;
		
		if(isset($product->gift_product_id)){
			$gift_product = DB::table("products")->where([['id', "=" , $product->gift_product_id]])->first();
		}
        
		
		$gift = false;
		if(isset($gift_product) AND isset($product->gift_count) AND isset($product->gift_product_count)){
			$gift["name"] = $gift_product->name;
			$gift["image"] = $gift_product->featured_img;
			$gift["count"] = $product->gift_count;
			$gift["product_count"] = $product->gift_product_count;
		}
		
		
		$level = $request->level;
		$price = $product_price->price;
		$box_count = $product->box_count;
		$is_wholesale = 0;
	
        $data = array();
        $data['id'] = $request->id;
        $str = '';
        $tax = 0;
        $data['quantity'] = $request->quantity;
        $data['price'] = $price;
        $data['box_count'] = $box_count;
        $data['tax'] = $tax;
        $data['shipping_type'] = "free";
        $data['is_wholesale'] = $is_wholesale;
        $data['level'] = $level;
		$data['shipping'] = 0;
		$data['gift'] = $gift;
		

        if($request->session()->has('cart')){
            $cart = $request->session()->get('cart', collect([]));
            $cart->push($data);
        }
        else{
            $cart = collect([$data]);
            $request->session()->put('cart', $cart);
        }

        return view('frontend.partials.addedToCart', compact('product', 'data'));
    }

    //removes from Cart
    public function removeFromCart(Request $request)
    {
        if($request->session()->has('cart')){
            $cart = $request->session()->get('cart', collect([]));
            $cart->forget($request->key);
			$new_cart = $cart->values();
            $request->session()->put('cart', $new_cart);
        }
    }
	
	public function updateQuantity(Request $request)
    {
        $cart = $request->session()->get('cart', collect([]));
        $cart = $cart->map(function ($object, $key) use ($request) {
            if($key == $request->key){
                $object['quantity'] = $request->quantity;
                $object['price'] = $request->price;
            }
            return $object;
        });
        return $request->session()->put('cart', $cart);
    }

    
}
