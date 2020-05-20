<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Auth;
use Hash;
use App\Category;
use App\Brand;
use App\Subbrand;
use App\BrandPrice;
use App\SubCategory;
use App\SubSubCategory;
use App\Product;
use App\SupplierProduct;
use App\User;
use App\UserPayment;
use App\Supplier;
use App\SupplierLevel;
use App\Seller;
use App\Shop;
use App\Color;
use App\OrderDetail;
use App\Order;
use App\City;
use App\BusinessSetting;
use App\Http\Controllers\SearchController;
use ImageOptimizer;

class HomeController extends Controller
{
    public function login()
    {
        if(Auth::check()){
            return redirect()->route('home');
        }
        return view('frontend.user_login');
    }

    public function registration($id = false)
    {

		$user = false;
		if($id) {
			$user = User::where("hash", $id)->first();
			if($user) {
				Session::put('referal', $user->id);
			}
		}

        if(Auth::check()) {
            return redirect()->route('home');
        }

        return view('frontend.user_registration', compact('user'));

    }

    // public function user_login(Request $request)
    // {
    //     $user = User::whereIn('user_type', ['customer', 'seller'])->where('email', $request->email)->first();
    //     if($user != null){
    //         if(Hash::check($request->password, $user->password)){
    //             if($request->has('remember')){
    //                 auth()->login($user, true);
    //             }
    //             else{
    //                 auth()->login($user, false);
    //             }
    //             return redirect()->route('dashboard');
    //         }
    //     }
    //     return back();
    // }

    public function cart_login(Request $request)
    {
        $user = User::whereIn('user_type', ['customer', 'seller', 'supplier'])->where('email', $request->email)->first();
        if($user != null){
            updateCartSetup();
            if(Hash::check($request->password, $user->password)){
                if($request->has('remember')){
                    auth()->login($user, true);
                }
                else{
                    auth()->login($user, false);
                }
            }
        }
        return back();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_dashboard()
    {
        return view('dashboard');
    }

    /**
     * Show the customer/seller dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        if(Auth::user()->user_type == 'supplier'){
			/* Update User level */
			$first_day = date("Y-m-01 00:00:00",strtotime("-1 month"));
			$last_day = date("Y-m-t 23:59:59",strtotime("-1 month"));
			$user_levels = [];
			$current_orders = Order::where('user_id', Auth::user()->id)->select('id')->get();
			if (isset($current_orders)) {
				foreach($current_orders as $current_order) {
					$orderDetail = OrderDetail::where([
						['order_id', '=', $current_order->id],
						['created_at', '>=', $first_day],
						['created_at', '<=', $last_day]
					])->select('price','product_id')->get();
					if(isset($orderDetail)){
						foreach($orderDetail as $detail){
							$product = Product::where('id', $detail->product_id)->select('subbrand_id')->first();
							if(!array_key_exists($product->subbrand_id, $user_levels)){
								$user_levels[$product->subbrand_id] = 0;
							}
							$user_levels[$product->subbrand_id] += intval($detail->price);
						}
					}
				}
			}
			$my_levels = [];
			if(count($user_levels) > 0){
				foreach($user_levels as $order_brand_id => $order_price){
					$brand_price = BrandPrice::where([['price', '<=', $order_price],['brand_id','=',$order_brand_id]])->orderBy('price', 'desc')->limit(1)->first();
					if(isset($brand_price)){
						$my_levels[$order_brand_id] = $brand_price->level;
					}
					else{
						$my_levels[$order_brand_id] = 1;
					}
				}
			}
			$is_edited_status = false;
			foreach($my_levels as $level_brand_id => $my_level){
				$supplier_level = SupplierLevel::where([["user_id","=", Auth::user()->id],["brand_id","=", $level_brand_id]])->first();
				if(!isset($supplier_level)){
					$new_supplier_level = new SupplierLevel();
					$new_supplier_level->level = $my_level;
					$new_supplier_level->brand_id = $level_brand_id;
					$new_supplier_level->user_id = Auth::user()->id;
					$new_supplier_level->save();
					$is_edited_status = true;
				}
				else{
					if($my_level != $supplier_level->level){
						$is_edited_status = true;
					}
					$supplier_level->level = $my_level;
					$supplier_level->save();
				}
			}
			if($is_edited_status){
				Session::put('new_level', 'Ваш уровень изменился!');
			}
            return view('frontend.supplier.dashboard');
        }
        else{
			header("Location: https://test.beezone.kz");
        }
    }

	public function dillers()
    {
		$users = DB::select('SELECT * FROM users WHERE referal = ?', [Auth::user()->id]);

		$tree = $this->createTreeBranch(Auth::user()->id);
    	return view('frontend.supplier.dillers', compact('users','tree'));
    }

    private function createTreeBranch($parent_id)
    {
        $tree = [];

        $users_array = User::where('referal', $parent_id)->get()->toArray();

		foreach($users_array as $user){
			$user["children"] = $this->createTreeBranch($user["id"]);
			$tree[] = $user;
		}

        return $tree;
    }

    public function profile(Request $request)
    {
        if(Auth::user()->user_type == 'customer'){
            return view('frontend.customer.profile');
        }
        elseif(Auth::user()->user_type == 'seller'){
            return view('frontend.seller.profile');
        }
        elseif(Auth::user()->user_type == 'supplier'){
            return view('frontend.supplier.profile');
        }
    }

    public function customer_update_profile(Request $request)
    {
        $user = Auth::user();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;
        $user->phone = $request->phone;

        if($request->new_password != null && ($request->new_password == $request->confirm_password)){
            $user->password = Hash::make($request->new_password);
        }

        if($request->hasFile('photo')){
            $user->avatar_original = $request->photo->store('uploads/users');
        }

        if($user->save()){
            flash(__('Your Profile has been updated successfully!'))->success();
            return back();
        }

        flash(__('Sorry! Something went wrong.'))->error();
        return back();
    }



    public function update_profile(Request $request)
    {
        $user = Auth::user();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;
        $user->phone = $request->phone;
        $user->company_name = $request->company_name;

        if($request->new_password != null && ($request->new_password == $request->confirm_password)){
            $user->password = Hash::make($request->new_password);
        }

        if($request->hasFile('photo')){
            $user->avatar_original = $request->photo->store('uploads/users');
        }

        if($request->hasFile('passport')){
            $user->passport = $request->passport->store('uploads/passports');
        }

        if($user->save()){
            flash(__('Your Profile has been updated successfully!'))->success();
            return back();
        }

        flash(__('Sorry! Something went wrong.'))->error();
        return back();
    }



    public function seller_update_profile(Request $request)
    {
        $user = Auth::user();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;
        $user->phone = $request->phone;

        if($request->new_password != null && ($request->new_password == $request->confirm_password)){
            $user->password = Hash::make($request->new_password);
        }

        if($request->hasFile('photo')){
            $user->avatar_original = $request->photo->store('uploads');
        }

        $seller = $user->seller;
        $seller->cash_on_delivery_status = $request->cash_on_delivery_status;
        $seller->sslcommerz_status = $request->sslcommerz_status;
        $seller->ssl_store_id = $request->ssl_store_id;
        $seller->ssl_password = $request->ssl_password;
        $seller->paypal_status = $request->paypal_status;
        $seller->paypal_client_id = $request->paypal_client_id;
        $seller->paypal_client_secret = $request->paypal_client_secret;
        $seller->stripe_status = $request->stripe_status;
        $seller->stripe_key = $request->stripe_key;
        $seller->stripe_secret = $request->stripe_secret;
        $seller->instamojo_status = $request->instamojo_status;
        $seller->instamojo_api_key = $request->instamojo_api_key;
        $seller->instamojo_token = $request->instamojo_token;
        $seller->razorpay_status = $request->razorpay_status;
        $seller->razorpay_api_key = $request->razorpay_api_key;
        $seller->razorpay_secret = $request->razorpay_secret;
        $seller->paystack_status = $request->paystack_status;
        $seller->paystack_public_key = $request->paystack_public_key;
        $seller->paystack_secret_key = $request->paystack_secret_key;

        if($user->save() && $seller->save()){
            flash(__('Your Profile has been updated successfully!'))->success();
            return back();
        }

        flash(__('Sorry! Something went wrong.'))->error();
        return back();
    }

    /**
     * Show the application frontend home.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $files = scandir(base_path('public/uploads/categories'));
        // foreach($files as $file) {
        //     ImageOptimizer::optimize(base_path('public/uploads/categories/').$file);
        // }

		$categories = Category::all();

        return view('frontend.index', compact('categories'));
    }

    public function trackOrder(Request $request)
    {
        if($request->has('order_code')){
            $order = Order::where('code', $request->order_code)->first();
            if($order != null){
                return view('frontend.track_order', compact('order'));
            }
        }
        return view('frontend.track_order');
    }

    public function product($slug)
    {
		return back();
		exit;
        $product  = Product::where('slug', $slug)->first();
        if($product!=null){
            updateCartSetup();
            return view('frontend.product_details', compact('product'));
        }
        abort(404);
    }

    public function shop($slug)
    {
        $shop  = Shop::where('slug', $slug)->first();
        if($shop!=null){
            return view('frontend.seller_shop', compact('shop'));
        }
        abort(404);
    }

    public function filter_shop($slug, $type)
    {
        $shop  = Shop::where('slug', $slug)->first();
        if($shop!=null && $type != null){
            return view('frontend.seller_shop', compact('shop', 'type'));
        }
        abort(404);
    }

    public function listing(Request $request)
    {
        $products = filter_products(Product::orderBy('created_at', 'desc'))->paginate(12);
        return view('frontend.product_listing', compact('products'));
    }

    public function all_categories(Request $request)
    {
        $categories = Category::all();
        return view('frontend.all_category', compact('categories'));
    }
    public function all_brands(Request $request)
    {
        $categories = Category::all();
        return view('frontend.all_brand', compact('categories'));
    }

    public function show_product_upload_form(Request $request)
    {
        $categories = Category::all();
        return view('frontend.supplier.product_upload', compact('categories'));
    }

    public function show_product_edit_form(Request $request, $id)
    {
        $categories = Category::all();
        $product = Product::find(decrypt($id));
        return view('frontend.supplier.product_edit', compact('categories', 'product'));
    }

    public function seller_product_list(Request $request)
    {
        $products_list = [];
        $supplier_products = SupplierProduct::where('user_id', Auth::user()->id)->get();
        foreach ($supplier_products as $supplier_product) {
            $products_list[] = $supplier_product->product_id;
        }
        $products = Product::whereIn('id', $products_list)->orderBy('created_at', 'desc')->paginate(10);

        return view('frontend.supplier.products', compact('products'));
    }

    public function ajax_search(Request $request)
    {
        $keywords = array();
        $products = Product::where('published', 1)->where('tags', 'like', '%'.$request->search.'%')->get();
        foreach ($products as $key => $product) {
            foreach (explode(',',$product->tags) as $key => $tag) {
                if(stripos($tag, $request->search) !== false){
                    if(sizeof($keywords) > 5){
                        break;
                    }
                    else{
                        if(!in_array(strtolower($tag), $keywords)){
                            array_push($keywords, strtolower($tag));
                        }
                    }
                }
            }
        }

        $products = filter_products(Product::where('published', 1)->where('name', 'like', '%'.$request->search.'%'))->get()->take(3);

        $subsubcategories = SubSubCategory::where('name', 'like', '%'.$request->search.'%')->get()->take(3);

        $shops = Shop::where('name', 'like', '%'.$request->search.'%')->get()->take(3);

        if(sizeof($keywords)>0 || sizeof($subsubcategories)>0 || sizeof($products)>0 || sizeof($shops) >0){
            return view('frontend.partials.search_content', compact('products', 'subsubcategories', 'keywords', 'shops'));
        }
        return '0';
    }

    public function search(Request $request)
    {
        $query = $request->q;
        $brand_id = (Brand::where('slug', $request->brand)->first() != null) ? Brand::where('slug', $request->brand)->first()->id : null;
        $sort_by = $request->sort_by;
        $category_id = (Category::where('slug', $request->category)->first() != null) ? Category::where('slug', $request->category)->first()->id : null;
        $subcategory_id = (SubCategory::where('slug', $request->subcategory)->first() != null) ? SubCategory::where('slug', $request->subcategory)->first()->id : null;
        $subsubcategory_id = (SubSubCategory::where('slug', $request->subsubcategory)->first() != null) ? SubSubCategory::where('slug', $request->subsubcategory)->first()->id : null;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $seller_id = $request->seller_id;

        $conditions = ['published' => 1];

        if($brand_id != null){
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }
        if($category_id != null){
            $conditions = array_merge($conditions, ['category_id' => $category_id]);
        }
        if($subcategory_id != null){
            $conditions = array_merge($conditions, ['subcategory_id' => $subcategory_id]);
        }
        if($subsubcategory_id != null){
            $conditions = array_merge($conditions, ['subsubcategory_id' => $subsubcategory_id]);
        }
        if($seller_id != null){
            $conditions = array_merge($conditions, ['user_id' => Seller::findOrFail($seller_id)->user->id]);
        }

        $products = Product::where($conditions);

        if($min_price != null && $max_price != null){
            $products = $products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);
        }

        if($query != null){
            $searchController = new SearchController;
            $searchController->store($request);
            $products = $products->where('name', 'like', '%'.$query.'%');
        }

        if($sort_by != null){
            switch ($sort_by) {
                case '1':
                    $products->orderBy('created_at', 'desc');
                    break;
                case '2':
                    $products->orderBy('created_at', 'asc');
                    break;
                case '3':
                    $products->orderBy('unit_price', 'asc');
                    break;
                case '4':
                    $products->orderBy('unit_price', 'desc');
                    break;
                default:
                    // code...
                    break;
            }
        }

        $products = filter_products($products)->paginate(12)->appends(request()->query());

        return view('frontend.product_listing', compact('products', 'query', 'category_id', 'subcategory_id', 'subsubcategory_id', 'brand_id', 'sort_by', 'seller_id','min_price', 'max_price'));
    }

    public function product_content(Request $request){
        $connector  = $request->connector;
        $selector   = $request->selector;
        $select     = $request->select;
        $type       = $request->type;
        productDescCache($connector,$selector,$select,$type);
    }

    public function home_settings(Request $request)
    {
        return view('home_settings.index');
    }

    public function top_10_settings(Request $request)
    {
        foreach (Category::all() as $key => $category) {
            if(in_array($category->id, $request->top_categories)){
                $category->top = 1;
                $category->save();
            }
            else{
                $category->top = 0;
                $category->save();
            }
        }

        foreach (Brand::all() as $key => $brand) {
            if(in_array($brand->id, $request->top_brands)){
                $brand->top = 1;
                $brand->save();
            }
            else{
                $brand->top = 0;
                $brand->save();
            }
        }

        flash(__('Top 10 categories and brands have been updated successfully'))->success();
        return redirect()->route('home_settings.index');
    }

    public function variant_price(Request $request)
    {
        $product = Product::find($request->id);
        $str = '';
        $quantity = 0;

        if($request->has('color')){
            $data['color'] = $request['color'];
            $str = Color::where('code', $request['color'])->first()->name;
        }

        foreach (json_decode(Product::find($request->id)->choice_options) as $key => $choice) {
            if($str != null){
                $str .= '-'.str_replace(' ', '', $request[$choice->name]);
            }
            else{
                $str .= str_replace(' ', '', $request[$choice->name]);
            }
        }

        if($str != null){
            $price = json_decode($product->variations)->$str->price;
            $quantity = json_decode($product->variations)->$str->qty;
        }
        else{
            $price = $product->unit_price;
        }

        //discount calculation
        $flash_deal = \App\FlashDeal::where('status', 1)->first();
        if ($flash_deal != null && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date && \App\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first() != null) {
            $flash_deal_product = \App\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first();
            if($flash_deal_product->discount_type == 'percent'){
                $price -= ($price*$flash_deal_product->discount)/100;
            }
            elseif($flash_deal_product->discount_type == 'amount'){
                $price -= $flash_deal_product->discount;
            }
        }
        else{
            if($product->discount_type == 'percent'){
                $price -= ($price*$product->discount)/100;
            }
            elseif($product->discount_type == 'amount'){
                $price -= $product->discount;
            }
        }

        if($product->tax_type == 'percent'){
            $price += ($price*$product->tax)/100;
        }
        elseif($product->tax_type == 'amount'){
            $price += $product->tax;
        }
        return array('price' => single_price($price*$request->quantity), 'quantity' => $quantity);
    }

    public function sellerpolicy(){
        return view("frontend.policies.sellerpolicy");
    }

    public function returnpolicy(){
        return view("frontend.policies.returnpolicy");
    }

    public function supportpolicy(){
        return view("frontend.policies.supportpolicy");
    }

    public function terms(){
        return view("frontend.policies.terms");
    }

    public function privacypolicy(){
        return view("frontend.policies.privacypolicy");
    }

	public function setcity($id){
		$city = City::where('id', $id)->first();
		Session::put('city', $city->name);
		Session::put('city_id', $city->id);
		return redirect()->route('home');
	}

	public function product_price(Request $request)
    {
        $product  = Product::where('id', $request->product_id)->first();
        if($product!=null){
			 echo '<p style="padding:0 10px;font-weight:bold;font-size:16px;text-align:center;">'.$product->name.'</p><br />';
			 $reproduct_price = \App\ProductPrice::where('product_id', $product->id)->get()->toArray();
			 if(count($reproduct_price) > 0):
				echo '<table class="table" data-count="'.$product->box_count.'">
					<thead>
						<tr>
							<th>№</th>
							<th>Мин. об.</th>
							<th>Цена</th>
							<th>Сумма</th>
							<th></th>
						</tr>
					</thead>
				<tbody>';

				$product_price = [];
				foreach ($reproduct_price as $rep_price){
					$product_price[$rep_price["level"]] = $rep_price;
				}


				$counter = 0;
				foreach ($product_price as $key => $price):
					$counter++;
					if(Auth::check()){
						$supplier_level_object = SupplierLevel::where([["user_id","=", Auth::user()->id],["brand_id","=", $product->subbrand_id]])->first();
						if(isset($supplier_level_object)){
							$supplier_level = SupplierLevel::where([["user_id","=", Auth::user()->id],["brand_id","=", $product->subbrand_id]])->first()->toArray();

							$current_position_level = $supplier_level["level"];
							if($current_position_level >= array_key_last($product_price)){
								$current_position_level = array_key_last($product_price);
							}
							if($price["level"] > $supplier_level["level"]){
								echo '<tr>
									<td>'.$price["level"].'</td>
									<td>
										<div class="product-level-buttons">
											<span class="product-level-minus-button">-</span>
											<div class="product-count-value" data-count="'.$price["count"].'" >'.$price["count"].'</div>
											<span class="product-level-plus-button">+</span>
										</div>
									</td>
									<td class="product-price-value" data-sum="'.$price["price"].'">'.number_format($price["price"], 0, '', '&nbsp;').'₸</td>
									<td class="product-price-total">'.number_format($price["price"]*$price["count"], 0, '', '&nbsp;').'₸</td>
									<td>
										<form id="option-choice-form-'.$price["level"].'">
											<input type="hidden" name="_token" value="'.@csrf_token().'">
											<input type="hidden" name="id" value="'.$product->id.'">
											<input type="hidden" name="quantity" value="'.$price["count"].'">
											<input type="hidden" name="level" value="'.$price["level"].'">
											<button onclick="addSelect('.$price["level"].')" type="button"
												class="btn btn-sm btn-styled btn-base-1 btn-icon-left strong-700 hov-bounce hov-shaddow buy__button" style="padding:2px 5px !important;">
												<i style="font-size:26px;" class="las la-shopping-cart"></i>
											</button>
										</form>
									</td>
								</tr>';
							}
							else{
								echo '<tr>
									<td>'.$price["level"].'</td>
									<td>
										<div class="product-level-buttons">
											<span class="product-level-minus-button">-</span>
											<div class="product-count-value" data-count="'.$price["count"].'">'.$price["count"].'</div>
											<span class="product-level-plus-button">+</span>
										</div>
									</td>
									<td class="product-price-value" data-sum="'.$product_price[$current_position_level]["price"].'">'.number_format($product_price[$current_position_level]["price"], 0, '', '&nbsp;').'₸</td>
									<td class="product-price-total">'.number_format($product_price[$current_position_level]["price"]*$price["count"], 0, '', '&nbsp;').'₸</td>
									<td>
										<form id="option-choice-form-'.$price["level"].'">
											<input type="hidden" name="_token" value="'.@csrf_token().'">
											<input type="hidden" name="id" value="'.$product->id.'">
											<input type="hidden" name="quantity"  value="'.$price["count"].'">
											<input type="hidden" name="level" value="'.$current_position_level.'">
											<button onclick="addSelect('.$price["level"].')" type="button"
												class="btn btn-sm btn-styled btn-base-1 btn-icon-left strong-700 hov-bounce hov-shaddow buy__button" style="padding:2px 5px !important;">
												<i style="font-size:26px;" class="las la-shopping-cart"></i>
											</button>
										</form>
									</td>
								</tr>';
							}
						}
						else{
							echo '<tr>
									<td>'.$price["level"].'</td>
									<td>
										<div class="product-level-buttons">
											<span class="product-level-minus-button">-</span>
											<div class="product-count-value" data-count="'.$price["count"].'">'.$price["count"].'</div>
											<span class="product-level-plus-button">+</span>
										</div>
									</td>
									<td class="product-price-value" data-sum="'.$price["price"].'">'.number_format($price["price"], 0, '', '&nbsp;').'₸</td>
									<td class="product-price-total">'.number_format($price["price"]*$price["count"], 0, '', '&nbsp;').'₸</td>
									<td>
										<form id="option-choice-form-'.$price["level"].'">
											<input type="hidden" name="_token" value="'.@csrf_token().'">
											<input type="hidden" name="id" value="'.$product->id.'">
											<input type="hidden" name="quantity" value="'.$price["count"].'">
											<input type="hidden" name="level" value="'.$price["level"].'">
											<button onclick="addSelect('.$price["level"].')" type="button"
												class="btn btn-sm btn-styled btn-base-1 btn-icon-left strong-700 hov-bounce hov-shaddow buy__button" style="padding:2px 5px !important;">
												<i style="font-size:26px;" class="las la-shopping-cart"></i>
											</button>
										</form>
									</td>
								</tr>';
						}
					}
					else{
						echo '<tr>
							<td>'.$price["level"].'</td>
							<td>
										<div class="product-level-buttons">
											<span class="product-level-minus-button">-</span>
											<div class="product-count-value" data-count="'.$price["count"].'">'.$price["count"].'</div>
											<span class="product-level-plus-button">+</span>
										</div>
									</td>
							<td class="product-price-value" data-sum="'.$price["price"].'">'.number_format($price["price"], 0, '', '&nbsp;').'₸</td>
							<td class="product-price-total">'.number_format($price["price"]*$price["count"], 0, '', '&nbsp;').'₸</td>
							<td>
								<form id="option-choice-form-'.$price["level"].'">
									<input type="hidden" name="_token" value="'.@csrf_token().'">
									<input type="hidden" name="id" value="'.$product->id.'">
									<input type="hidden" name="level" value="'.$price["level"].'">
									<input type="hidden" name="quantity" class="form-control input-number text-center" value="'.$price["count"].'">
									<button onclick="addSelect('.$price["level"].')" type="button"
										class="btn btn-sm btn-styled btn-base-1 btn-icon-left strong-700 hov-bounce hov-shaddow buy__button" style="padding:2px 5px !important;">
										<i style="font-size:26px;" class="las la-shopping-cart"></i>
									</button>
								</form>
							</td>
						</tr>';
					}

				endforeach;


				echo '</tbody>
				</table>';


			else:
				echo '<p class="pt-3 text-center">Оптовые цены отсутствует</p>';
			endif;


        }
        else{
			return "товар не найден";
		}
    }

	public function dillers_delete(Request $request){
		$id = $request->id;
		Order::where('user_id', $id)->delete();
        User::destroy($id);
        if(Supplier::where('user_id', $id)->delete()){
            flash(__('Пользователь удален!'))->success();
            return redirect()->route('dillers.index');
        }

        flash(__('Something went wrong'))->error();
        return back();
	}

	public function dillers_info(Request $request)
    {
		$id = $request->user_id;
		$user = User::where('id', $id)->first();
    	return view('frontend.supplier.dillers_info', compact('user'));
    }

	public function dillers_level(Request $request)
	{
		$user = User::findOrFail($request->user_id);
		$brands = Subbrand::all();
		$supplier_levels = SupplierLevel::where("user_id",$request->user_id)->get();
		return view('frontend.supplier.dillers_level',compact('user','brands','supplier_levels'));
	}

	public function dillers_edit(Request $request,$id)
	{
		if($request->has('level')){
			$levels = $request->level;
			$brands = $request->brand;
			SupplierLevel::where("user_id",$id)->delete();
			foreach ($levels as $key => $level) {
				$supplier_level = new SupplierLevel();
				$supplier_level->level = $level;
				$supplier_level->user_id = $id;
				$supplier_level->brand_id = $brands[$key];
				$supplier_level->save();
			}
			flash('Данные успешно изменены')->success();
        	return redirect()->route('dillers.index');
		}else{
            flash(__('Something went wrong'))->error();
            return back();
        }
	}

	public function orders_combine(Request $request){
		if(!is_null($request->orders)){
			$orders = $request->orders;

			if(isset($request->is_combine) AND !is_null($request->is_combine) AND $request->is_combine == 1){

				if(count($orders) < 2){
					flash("Выберите минимум 2 заказа!")->error();
					return back();
				}
				else{
					$order = Order::find($orders[0]);
					$order_new = $order->replicate();
					$order_new->viewed = 0;
					$order_new->code = date('Ymd-his');

					if($order_new->save()){
						foreach($orders as $order_id){
							$order_details = OrderDetail::where('order_id',$order_id)->get();
							if(isset($order_details)){
								foreach($order_details as $order_detail){
									$order_detail_object = OrderDetail::where('id',$order_detail->id)->first();
									$order_detail_object_new = $order_detail_object->replicate();
									$order_detail_object_new->order_id = $order_new->id;
									$order_detail_object_new->seller_id = 9;
									$order_detail_object_new->save();
								}
							}
						}
					}
					flash(__('Заказы успешно отправлены!'))->success();
					return back();
				}
			}
			else{
				foreach($orders as $order_id){
					$order = Order::find($order_id);
					$order_new = $order->replicate();
					$order_new->user_id = Auth::user()->id;
					$order_new->viewed = 0;
					$order_new->code = date('Ymd-his');

					if($order_new->save()){
						$order_details = OrderDetail::where('order_id',$order_id)->get();
						if(isset($order_details)){
							foreach($order_details as $order_detail){
								$order_detail_object = OrderDetail::where('id',$order_detail->id)->first();
								$order_detail_object_new = $order_detail_object->replicate();
								$order_detail_object_new->order_id = $order_new->id;
								$order_detail_object_new->seller_id = 9;
								$order_detail_object_new->save();
							}
						}
					}
				}
				flash(__('Заказы успешно отправлены!'))->success();
				return back();
			}

		}

	   flash(__('Something went wrong'))->error();
        return back();
	}

	public function payment_method(){
		$user_payments = UserPayment::where('user_id',Auth::user()->id)->get();
    	return view('frontend.supplier.payment_methods',compact('user_payments'));
	}
	public function verification($code){
		$email = base64_decode($code);
		$user = User::where("email",$email)->first();
		$user->email_verify = 1;
		$user->save();
		return redirect()->route('profile');
	}
	public function verify_email(){

		$to = $_POST["email"];
		$from = "info@beezone.kz";
		$subject = "Beezone";
		$link = base64_encode($to);

		$body = "Чтобы потвердить ваш Email, пройдите по ссылке:
		<a href='https://beezone.kz/verification/".$link."'>https://beezone.kz/verification/".$link."</a> ";

		$charset = 'utf-8';
		mb_language("en");
		$headers  = "MIME-Version: 1.0 \n" ;
		$headers .= "From: <".$from."> \n";
		$headers .= "Reply-To: <".$from."> \n";
		$headers .= "Content-Type: text/html; charset=$charset \n";
		$subject = '=?'.$charset.'?B?'.base64_encode($subject).'?=';
		$html = '
			<!DOCTYPE html>
			<html>
			<head>
				<title>'.$subject.'</title>
			</head>
			<body>
			<div style="background:#ffc107;padding: 20px;max-width:800px;">
				<h1 style="color:#fff;text-align:center">Beezone</h1>
				<div style="background: #fff;border-radius: 20px;padding:20px;text-align: center;">
					<p>'.$body.'</p>
				</div>
				<br><br>
			</div>
			</body>
			</html>
		';
		if(mail($to,$subject,$html,$headers)){
			return "1";
		}
		else{
			return "0";
		}
	}

	public function add_payment_method(Request $request){
		$user_payment = new UserPayment();
		$user_payment->user_id = Auth::user()->id;
		$user_payment->name = $request->type;
		$user_payment->value = $request->card_number;
		$user_payment->user_name = $request->card_name." ".$request->card_surname;
		if($user_payment->save()){
			flash('Данные успешно добавлены!')->success();
		}
		else{
			flash(__('Something went wrong'))->error();
		}
		return back();
	}

	public function delete_payment_method(Request $request){
		UserPayment::where("id",$request->id)->delete();
	}
}
