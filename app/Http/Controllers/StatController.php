<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class StatController extends Controller
{
    public function admin_index()
    {
		$data = DB::table('orders')->select(DB::raw('count(*) as data_counter'))->first();
		
		
		$map = DB::table('orders')->select(DB::raw('lat,lng'))->where('lat', '<>', 0)->get();
		
		$getlocations = [];
		foreach($map as $geo){
			$getlocations[] = [floatval($geo->lat),floatval($geo->lng)];
		}
		$getlocations = json_encode($getlocations);
		
        return view('stat.index', compact('data','getlocations'));
    }
}
