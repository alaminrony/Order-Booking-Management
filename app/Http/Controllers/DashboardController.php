<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use DB;
class DashboardController extends Controller {

    public function index() {

        $data['transactionCount'] = Transaction::select(DB::raw("COUNT(id) as count_value"),DB::raw("SUM(amount) as total_tr"))->first();
        $data['transactionInCount'] = Transaction::where('transaction_type', 'in')->select(DB::raw("COUNT(id) as count_value"),DB::raw("SUM(amount) as total_tr"))->first();
        $data['transactionOutCount'] = Transaction::where('transaction_type', 'out')->select(DB::raw("COUNT(id) as count_value"),DB::raw("SUM(amount) as total_tr"))->first();;

        $data['user'] = User::count();
        $data['supplier'] = User::where('role_id', '3')->count();
        $data['customer'] = User::where('role_id', '4')->count();
        
        $data['title'] = 'Dashboard';
        $data['meta_tag'] = 'Dashboard page, rafiq & sons';
        $data['meta_description'] = 'Dashboard rafiq & sons';
        return view('backEnd.index')->with(compact('data'));
    }

}
