<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function index()
    {
        //SELECT count(*) as total, status FROM `order` GROUP by status;
        $records = DB::table('order')
        ->selectRaw('count(id) as total, status')
        ->groupBy('status')
        ->get();
        
        $datas = [];
        $datas[] = ['Status', 'Total'];
        foreach($records as $record){
            $datas[] = [$record->status, $record->total];
        }
    
        return view('clients.pages.shop', ['datas' => $datas]);
    }
}
