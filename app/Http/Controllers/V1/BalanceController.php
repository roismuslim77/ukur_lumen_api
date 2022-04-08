<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Member;
use Illuminate\Http\Request;
use App\Helpers\Format;

class BalanceController extends Controller
{
    public static function store(Request $request)
    {
        $reqdata = $request->all();

        $storedata = [];
        foreach ($reqdata as $v) {
            if($v['details'][0]['balance']  < 10000 ){
                $storedata[] = [
                    'name' => $v['details'][0]['name'],
                    'balance' => $v['details'][0]['balance'],
                    'transportation' => $v['favoriteTransportation']
                ];
            }
        }

        $store = Member::insert($storedata);
        if ($store) {
            return Format::response([
                'message' => 'Success store data members',
                'data' => $storedata
            ]);
        }else {
            return Format::response([
                'message' => 'Failed store data members',
                'data' => []
            ]);
        }
    }
}