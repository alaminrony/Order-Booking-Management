<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\RoleToAccess;
use App\Models\User;
use App\Models\Transaction;
use DB;

class Helper {

    public static function dateFormat($date) {
            return date('d M Y \a\t h:i A', strtotime($date));
    }
    
    public static function dateFormat2($date) {
       return date('d M Y', strtotime($date));
    }
    
    public static function timeFormat($time) {
       return date('h:i:s A', strtotime($time));
    }

    public static function status($data) {

        if ($data == '1') {
            echo "Active";
        } else {
            echo "Inactive";
        }
    }

    public static function pendingCheque($data) {

        if ($data == '1') {
            echo "Approved";
        } else {
            echo "Pending";
        }
    }

    public static function accessToMethod() {
        if (Auth::check()) {
            echo "User logged , user_id : " . $userID;
        } else {
            echo "Not logged"; //It is returning this
        }
        exit;

//        echo "<pre>";print_r(Auth::user()->id);exit;
//        $roleToAccess = RoleToAccess::join('module_operations','module_operations.id','=','role_to_accesses.module_operation_id')
//                ->select('role_to_accesses.id','role_to_accesses.role_id','role_to_accesses.module_id','role_to_accesses.module_operation_id','module_operations.operation','module_operations.method')
//                ->where('role_to_accesses.role_id', Auth::user()->role_id)->get();
//        echo "<pre>";print_r($roleToAccess->toArray());exit;
        if ($roleToAccess->isNotEmpty()) {
            $accessArr = [];
            $i = 0;
            foreach ($roleToAccess as $access) {
                $accessArr[$access->module_id][$i] = $access->module_operation_id;
                $i++;
            }
        }
    }

    public static function balanceCalculation($id) {
        $in_balances = Transaction::where('user_id', $id)->where('transaction_type', 'in')->sum('amount');
        $out_balances = Transaction::where('user_id', $id)->where('transaction_type', 'out')->sum('amount');

        $payableBalance = $in_balances - $out_balances;
        $data['in_balances'] = $in_balances;
        $data['out_balances'] = $out_balances;
        $data['payableBalance'] = $payableBalance;

        return $data;
    }

    

}
