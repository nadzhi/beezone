<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes(['verify' => true]);
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::post('/language', 'LanguageController@changeLanguage')->name('language.change');
Route::post('/currency', 'CurrencyController@changeCurrency')->name('currency.change');

Route::get('/social-login/redirect/{provider}', 'Auth\LoginController@redirectToProvider')->name('social.login');
Route::get('/social-login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('social.callback');
Route::get('/users/login', 'HomeController@login')->name('user.login');
Route::get('/users/registration', 'HomeController@registration')->name('user.registration');
Route::get('/users/registration/{id}', 'HomeController@registration')->name('user.registration_referal');
//Route::post('/users/login', 'HomeController@user_login')->name('user.login.submit');
Route::post('/users/login/cart', 'HomeController@cart_login')->name('cart.login.submit');

Route::post('/subcategories/get_subcategories_by_category', 'SubCategoryController@get_subcategories_by_category')->name('subcategories.get_subcategories_by_category');
Route::post('/subcategories/get_products_by_category', 'SubCategoryController@get_products_by_category')->name('subcategories.get_products_by_category');
Route::post('/subcategories/add_product', 'SubCategoryController@add_product')->name('subcategories.add_product');
Route::post('/subsubcategories/get_subsubcategories_by_subcategory', 'SubSubCategoryController@get_subsubcategories_by_subcategory')->name('subsubcategories.get_subsubcategories_by_subcategory');
Route::post('/subsubcategories/get_brands_by_subsubcategory', 'SubSubCategoryController@get_brands_by_subsubcategory')->name('subsubcategories.get_brands_by_subsubcategory');

Route::get('/', 'HomeController@index')->name('home');
Route::get('/sitemap.xml', function(){
	return base_path('sitemap.xml');
});

Route::get('/city/{id}', 'HomeController@setcity')->name('city');
Route::get('/product/{slug}', 'HomeController@product')->name('product');
Route::post('/ajax/product/price', 'HomeController@product_price')->name('product.price');
Route::get('/products', 'HomeController@listing')->name('products');
Route::get('/search?category={category_slug}', 'HomeController@search')->name('products.category');
Route::get('/search?subcategory={subcategory_slug}', 'HomeController@search')->name('products.subcategory');
Route::get('/search?subsubcategory={subsubcategory_slug}', 'HomeController@search')->name('products.subsubcategory');
Route::get('/search?brand={brand_slug}', 'HomeController@search')->name('products.brand');
Route::post('/product/variant_price', 'HomeController@variant_price')->name('products.variant_price');
Route::get('/shops/visit/{slug}', 'HomeController@shop')->name('shop.visit');
Route::get('/shops/visit/{slug}/{type}', 'HomeController@filter_shop')->name('shop.visit.type');

Route::get('/cart', 'CartController@index')->name('cart');
Route::post('/cart/nav-cart-items', 'CartController@updateNavCart')->name('cart.nav_cart');
Route::post('/cart/show-cart-modal', 'CartController@showCartModal')->name('cart.showCartModal');
Route::post('/cart/addtocart', 'CartController@addToCart')->name('cart.addToCart');
Route::post('/cart/removeFromCart', 'CartController@removeFromCart')->name('cart.removeFromCart');
Route::post('/cart/updateQuantity', 'CartController@updateQuantity')->name('cart.updateQuantity');

Route::group(['middleware' => ['message']], function () {
    Route::post('/checkout/payment', 'CheckoutController@checkout')->name('payment.checkout');
    Route::get('/checkout', 'CheckoutController@get_shipping_info')->name('checkout.shipping_info');
    Route::post('/checkout/payment_select', 'CheckoutController@store_shipping_info')->name('checkout.store_shipping_infostore');
    Route::get('/checkout/payment_select', 'CheckoutController@get_payment_info')->name('checkout.payment_info');
    Route::post('/checkout/apply_coupon_code', 'CheckoutController@apply_coupon_code')->name('checkout.apply_coupon_code');
    Route::post('/checkout/remove_coupon_code', 'CheckoutController@remove_coupon_code')->name('checkout.remove_coupon_code');
});



//Paypal START
Route::get('/paypal/payment/done', 'PaypalController@getDone')->name('payment.done');
Route::get('/paypal/payment/cancel', 'PaypalController@getCancel')->name('payment.cancel');
//Paypal END

// SSLCOMMERZ Start
Route::get('/sslcommerz/pay', 'PublicSslCommerzPaymentController@index');
Route::POST('/sslcommerz/success', 'PublicSslCommerzPaymentController@success');
Route::POST('/sslcommerz/fail', 'PublicSslCommerzPaymentController@fail');
Route::POST('/sslcommerz/cancel', 'PublicSslCommerzPaymentController@cancel');
Route::POST('/sslcommerz/ipn', 'PublicSslCommerzPaymentController@ipn');
//SSLCOMMERZ END

//Stipe Start
Route::get('stripe', 'StripePaymentController@stripe');
Route::post('stripe', 'StripePaymentController@stripePost')->name('stripe.post');
//Stripe END

Route::get('/compare', 'CompareController@index')->name('compare');
Route::get('/compare/reset', 'CompareController@reset')->name('compare.reset');
Route::post('/compare/addToCompare', 'CompareController@addToCompare')->name('compare.addToCompare');

Route::resource('subscribers','SubscriberController');

Route::get('/brands', 'HomeController@all_brands')->name('brands.all');
Route::get('/categories', 'HomeController@all_categories')->name('categories.all');
Route::get('/search', 'HomeController@search')->name('search');
Route::get('/search?q={search}', 'HomeController@search')->name('suggestion.search');
Route::post('/ajax-search', 'HomeController@ajax_search')->name('search.ajax');
Route::post('/config_content', 'HomeController@product_content')->name('configs.update_status');

Route::get('/sellerpolicy', 'HomeController@sellerpolicy')->name('sellerpolicy');
Route::get('/returnpolicy', 'HomeController@returnpolicy')->name('returnpolicy');
Route::get('/supportpolicy', 'HomeController@supportpolicy')->name('supportpolicy');
Route::get('/terms', 'HomeController@terms')->name('terms');
Route::get('/privacypolicy', 'HomeController@privacypolicy')->name('privacypolicy');

Route::group(['middleware' => ['phone']], function(){
    Route::get('/phone_new','PhoneController@store')->name('phone.new');
    Route::post('/phone_new','PhoneController@save')->name('phone.save');
});

Route::group(['middleware' => ['message.verified']], function(){
    Route::get('/phone_confirm','PhoneController@confirm')->name('phone.confirm');
    Route::post('/phone_confirm','PhoneController@check')->name('phone.check');
});

Route::group(['middleware' => ['user', 'message', 'verified']], function(){
	Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
	Route::get('/dillers', 'HomeController@dillers')->name('dillers.index');
	Route::post('/dillers/delete', 'HomeController@dillers_delete')->name('delete.dillers');
	Route::post('/dillers/info', 'HomeController@dillers_info')->name('diller.info');
	Route::post('/dillers/level', 'HomeController@dillers_level')->name('diller.level');
	Route::post('/dillers/edit/{id}', 'HomeController@dillers_edit')->name('diller.edit');


	Route::get('/payment_methods', 'HomeController@payment_method')->name('payment_method.index');
	Route::post('/add/payment_methods', 'HomeController@add_payment_method')->name('payment_method.add');
	Route::post('/payment_method/delete', 'HomeController@delete_payment_method')->name('payment_method.delete');

	Route::post('/orders/combine', 'HomeController@orders_combine')->name('orders.combine');

	Route::get('/profile', 'HomeController@profile')->name('profile');
	Route::post('/verify_email', 'HomeController@verify_email')->name('verify_email');

	Route::get('/verification/{code}', 'HomeController@verification')->name('verification');

	Route::post('/customer/update-profile', 'HomeController@customer_update_profile')->name('customer.profile.update');
	Route::post('/update-profile', 'HomeController@update_profile')->name('supplier.profile.update');

	Route::resource('purchase_history','PurchaseHistoryController');
	Route::post('/purchase_history/details', 'PurchaseHistoryController@purchase_history_details')->name('purchase_history.details');
	Route::get('/purchase_history/destroy/{id}', 'PurchaseHistoryController@destroy')->name('purchase_history.destroy');

	Route::resource('wishlists','WishlistController');
	Route::post('/wishlists/remove', 'WishlistController@remove')->name('wishlists.remove');

	Route::get('/wallet', 'WalletController@index')->name('wallet.index');
	Route::post('/recharge', 'WalletController@recharge')->name('wallet.recharge');

	Route::resource('support_ticket','SupportTicketController');
	Route::post('support_ticket/reply','SupportTicketController@seller_store')->name('support_ticket.seller_store');
});

Route::group(['prefix' =>'seller', 'middleware' => ['seller', 'verified']], function(){
	Route::get('/products', 'HomeController@seller_product_list')->name('seller.products');
	Route::get('/product/upload', 'HomeController@show_product_upload_form')->name('seller.products.upload');
	Route::get('/product/{id}/edit', 'HomeController@show_product_edit_form')->name('seller.products.edit');
	Route::resource('payments','PaymentController');

	Route::get('/shop/apply_for_verification', 'ShopController@verify_form')->name('shop.verify');
	Route::post('/shop/apply_for_verification', 'ShopController@verify_form_store')->name('shop.verify.store');

	Route::get('/reviews', 'ReviewController@seller_reviews')->name('reviews.seller');
});


Route::post('/supplier_products/update/{id}', 'SubCategoryController@supplier_product_update')->name('supplier_products.update');

Route::group(['prefix' =>'supplier', 'middleware' => ['seller', 'verified']], function(){
	Route::get('/products', 'HomeController@seller_product_list')->name('supplier.products');
	Route::get('/product/upload', 'HomeController@show_product_upload_form')->name('supplier.products.upload');
	Route::get('/product/{id}/edit', 'HomeController@show_product_edit_form')->name('supplier.products.edit');
});


Route::group(['middleware' => ['auth']], function(){
	Route::post('/products/store/','ProductController@store')->name('products.store');
	Route::post('/products/update/{id}','ProductController@update')->name('products.update');
	Route::get('/products/destroy/{id}', 'ProductController@destroy')->name('products.destroy');
	Route::get('/products/duplicate/{id}', 'ProductController@duplicate')->name('products.duplicate');
	Route::post('/products/sku_combination', 'ProductController@sku_combination')->name('products.sku_combination');
	Route::post('/products/sku_combination_edit', 'ProductController@sku_combination_edit')->name('products.sku_combination_edit');
	Route::post('/products/featured', 'ProductController@updateFeatured')->name('products.featured');
	Route::post('/products/published', 'ProductController@updatePublished')->name('products.published');

	Route::get('invoice/customer/{order_id}', 'InvoiceController@customer_invoice_download')->name('customer.invoice.download');
	Route::get('invoice/seller/{order_id}', 'InvoiceController@seller_invoice_download')->name('seller.invoice.download');


	Route::resource('orders','OrderController');
	Route::get('/orders/destroy/{id}', 'OrderController@destroy')->name('orders.destroy');
	Route::post('/orders/details', 'OrderController@order_details')->name('orders.details');
	Route::post('/orders/update_delivery_status', 'OrderController@update_delivery_status')->name('orders.update_delivery_status');
	Route::post('/orders/update_payment_status', 'OrderController@update_payment_status')->name('orders.update_payment_status');

	Route::resource('/reviews', 'ReviewController');
});

Route::resource('shops', 'ShopController');
Route::get('/track_your_order', 'HomeController@trackOrder')->name('orders.track');

Route::get('/instamojo/payment/pay-success', 'InstamojoController@success')->name('instamojo.success');

Route::post('rozer/payment/pay-success', 'RazorpayController@payment')->name('payment.rozer');

Route::get('/paystack/payment/callback', 'PaystackController@handleGatewayCallback');

Route::get('/faq', 'FaqController@index')->name('faq');
