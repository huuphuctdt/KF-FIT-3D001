<?php

namespace App\Http\Controllers;

use App\Jobs\TestJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(){
        Log::critical("critical");
        Log::info("info");
        Log::error("error");



        //SELECT count(*) as total, status FROM `order` group by status;
        $records = DB::table('order')
        ->selectRaw('count(*) as total, status')
        ->groupBy('status')
        ->get();

        $datas = [];
        $datas[] = ['Status', 'Total'];
        foreach($records as $record){
            $datas[] = [$record->status, $record->total];
        }
 
        //SELECT count(*) as total, DATE_FORMAT(created_at, '%m-%Y') 
        // FROM `order` 
        // GROUP BY DATE_FORMAT(created_at, '%m-%Y') 
        // ORDER BY created_at DESC;
        $records = DB::table('order')
        ->selectRaw("count(*) as total, DATE_FORMAT(created_at, '%m-%Y') as month_year")
        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%m-%Y')"))
        ->orderBy('created_at', 'desc')
        ->get();

        $dataColumns = [];
        $dataColumns[] = ["MonthYear", "Total"];
        foreach($records as $record){
            $dataColumns[] = [$record->month_year, $record->total];
        }

        return view('admin.pages.dashboard', ['datas' => $datas, 'dataColumns' => $dataColumns]);
    }
}
