<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Product;
use App\ProductGift;
use App\Supplier;
use App\SupplierProduct;
use App\Color;
use App\City;
use App\Brand;
use App\OrderDetail;
use App\CouponUsage;
use Auth;
use Session;
use DB;
use PDF;
use Mail;
use App\Mail\InvoiceEmailManager;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource to seller.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = DB::table('orders')
            ->orderBy('code', 'desc')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->where('order_details.seller_id', Auth::user()->id)
            ->select('orders.id')
            ->distinct()
            ->paginate(9);

        return view('frontend.supplier.orders', compact('orders'));
    }

    /**
     * Display a listing of the resource to admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_orders(Request $request)
    {
        $orders = DB::table('orders')
            ->orderBy('code', 'desc')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->where('order_details.seller_id', Auth::user()->id)
            ->select('orders.id')
            ->distinct()
            ->paginate(9);

        return view('orders.index', compact('orders'));
    }

    /**
     * Display a listing of the sales to admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function sales(Request $request)
    {
        $orders = Order::orderBy('code', 'desc')->get();
        return view('sales.index', compact('orders'));
    }

    /**
     * Display a single sale to admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function sales_show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        return view('sales.show', compact('order'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $brands = [];
        $admin_seller_id = 9;
        foreach (Session::get('cart') as $key => $cartItem){
            $product = Product::find($cartItem['id']);
            $brands[$product->brand_id] = $product->brand_id;
        }


        foreach($brands as $brand_id){
            $brand = Brand::find($brand_id);

            $order = new Order;
            if(Auth::check()){
                $order->user_id = Auth::user()->id;
            }else{
                $order->guest_id = mt_rand(100000, 999999);
            }


            $shipping_info = $request->session()->get('shipping_info');
            $order->lat = $shipping_info["lat"];
            $order->lng = $shipping_info["lng"];
            $order->city_id = $shipping_info["city"];


            if($shipping_info["city"] < 1 OR $shipping_info["city"] > 17){
                $shipping_info["city"] = 1;
            }
            $city = City::find($shipping_info["city"]);

            $shipping_info["city"] = $city->name;
            $order->shipping_address = json_encode($shipping_info);
            $order->payment_type = $request->payment_option;
            $order->code = $brand->code.date('Ymd-his');
            $order->date = strtotime('now');
            if($order->save()){
                $subtotal = 0;
                $tax = 0;
                $shipping = 0;
                foreach (Session::get('cart') as $key => $cartItem){
                    $product = Product::find($cartItem['id']);
                    if($brand_id == $product["brand_id"]){
                        $subtotal += $cartItem['price']*$cartItem['quantity'];
                        $tax += $cartItem['tax']*$cartItem['quantity'];
                        $shipping += $cartItem['shipping']*$cartItem['quantity'];
                        $product_variation = null;

                        $supplier_product = false;
                        if(Auth::user()->referal != null){
                            $supplier_product = DB::table('supplier_products')
                                ->where([['user_id', "=", Auth::user()->referal],['product_id', "=", $product->id]])->first();
                        }
                        $order_detail = new OrderDetail;
                        $order_detail->order_id  = $order->id;
                        if($supplier_product){
                            if($order_detail->quantity <= $supplier_product->count){
                                $order_detail->seller_id = Auth::user()->referal;
                                $suppler_product_new = SupplierProduct::where([ ["product_id", "=", $product->id] , ["user_id","=",Auth::user()->referal] ])->first();
                                if(isset($suppler_product_new)){
                                    $suppler_product_new->count = $suppler_product_new->count - $cartItem['quantity'];
                                    $suppler_product_new->save();
                                }
                            }
                        }
                        else{
                            $order_detail->seller_id = $admin_seller_id;
                        }
                        $order_detail->product_id = $product->id;
                        $order_detail->level = $cartItem["level"];
                        $order_detail->variation = $product_variation;
                        $order_detail->price = $cartItem['price'] * $cartItem['quantity'];
                        $order_detail->tax = $cartItem['tax'] * $cartItem['quantity'];
                        $order_detail->shipping_cost = $cartItem['shipping']*$cartItem['quantity'];
                        $order_detail->quantity = $cartItem['quantity'];
                        $order_detail->save();




                        if($cartItem["gift"]){
                            $product_gift = new ProductGift;
                            $product_gift->order_id = $order->id;
                            $product_gift->product_name = $cartItem["gift"]["name"];
                            $product_gift->count = intval($cartItem["quantity"]/$cartItem["gift"]["count"])*$cartItem["gift"]["product_count"];
                            $product_gift->save();
                        }



                        $product->num_of_sale++;
                        $product->save();

                        $suppler_product = SupplierProduct::where([ ["product_id", "=", $product->id] , ["user_id","=",Auth::user()->id] ])->first();
                        if(!isset($suppler_product)){
                            $supplier_product_new = new SupplierProduct();
                            $supplier_product_new->product_id = $product->id;
                            $supplier_product_new->user_id = Auth::user()->id;
                            $supplier_product_new->count = $cartItem['quantity'];
                            $supplier_product_new->save();
                        }
                        else{
                            $suppler_product->count = $suppler_product->count + $cartItem['quantity'];
                            $suppler_product->save();
                        }
                    }
                }




                $order->grand_total = $subtotal + $tax + $shipping;
                $order->save();



                //stores the pdf for invoice
                $pdf = PDF::setOptions([
                    'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
                    'logOutputFile' => storage_path('logs/log.htm'),
                    'tempDir' => storage_path('logs/')
                ])->loadView('invoices.customer_invoice', compact('order'));
                $output = $pdf->output();
                file_put_contents('public/invoices/'.'Order#'.$order->code.'.pdf', $output);

                $array['view'] = 'emails.invoice';
                $array['subject'] = 'Order Placed - '.$order->code;
                $array['from'] = env('MAIL_USERNAME');
                $array['content'] = 'Hi. Your order has been placed';
                $array['file'] = 'public/invoices/Order#'.$order->code.'.pdf';
                $array['file_name'] = 'Order#'.$order->code.'.pdf';

                //sends email to customer with the invoice pdf attached
                /*if(env('MAIL_USERNAME') != null && env('MAIL_PASSWORD') != null){
                    Mail::to($request->session()->get('shipping_info')['email'])->queue(new InvoiceEmailManager($array));
                }*/
                unlink($array['file']);

                $request->session()->put('order_id', $order->id);

            }

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        $order->viewed = 1;
        $order->save();
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if($order != null){
            foreach($order->orderDetails as $key => $orderDetail){
                $orderDetail->delete();
            }
            $order->delete();
            flash('Order has been deleted successfully')->success();
        }
        else{
            flash('Something went wrong')->error();
        }
        return back();
    }

    public function order_details(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        return view('frontend.partials.order_details_seller', compact('order'));
    }

    public function update_delivery_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        foreach($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail){
            $orderDetail->delivery_status = $request->status;
            $orderDetail->save();
        }
        return 1;
    }

    public function update_payment_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        foreach($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail){
            $orderDetail->payment_status = $request->status;
            $orderDetail->save();
        }
        $status = 'paid';
        foreach($order->orderDetails as $key => $orderDetail){
            if($orderDetail->payment_status != 'paid'){
                if($orderDetail->payment_status == 'consignation'){
                    $status = 'consignation';
                }
                $status = 'unpaid';
            }
        }
        $order->payment_status = $status;
        $order->save();
        return 1;
    }
}
