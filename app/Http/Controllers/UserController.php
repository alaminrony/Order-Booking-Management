<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use App\Models\User;
use App\Models\BankAccount;
use App\Models\Issue;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;
use Hash;
use Image;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExcelExport;
use PDF;

class UserController extends Controller {

//    public $accessToMethod;
//    public $role_id;
//
//    public function __construct() {
//
//        $this->$accessToMethod = Helper::accessToMethod();
//    }

    public function index(Request $request) {

        $users = ['' => __('lang.SELECT_USER')] + User::select(DB::raw("CONCAT(name,' (',phone,')') as name"), 'id')->pluck('name', 'id')->toArray();
        $roles = ['' => __('lang.SELECT_ROLE')] + UserRole::pluck('role_name', 'id')->toArray();
        $targets = User::orderBy('id', 'desc');

        if (!empty($request->user_id)) {
            $targets = $targets->where('id', $request->user_id);
        }
        if (!empty($request->role_id)) {
            $targets = $targets->where('role_id', $request->role_id);
        }
        if (!empty($request->search_value)) {
            $searchText = $request->search_value;
            $targets->where(function ($query) use ($searchText) {
                $query->where('email', 'like', "%{$searchText}%")
                        ->orWhere('address', 'like', "%{$searchText}%")
                        ->orWhere('phone', 'like', "%{$searchText}%");
            });
        }

        $targets = $targets->paginate(20);

        $data['title'] = 'User List';
        $data['meta_tag'] = 'User List page, rafiq & sons';
        $data['meta_description'] = 'User List rafiq & sons';

        return view('backEnd.user.index')->with(compact('targets', 'roles', 'users', 'data'));
    }

    public function create(Request $request) {
        $roles = ['' => __('lang.SELECT_ROLE')] + UserRole::pluck('role_name', 'id')->toArray();
        $view = view('backEnd.user.create')->with(compact('roles'))->render();
        return response()->json(['data' => $view]);
    }

    public function view(Request $request) {
        $roles = UserRole::pluck('role_name', 'id')->toArray();
        $target = User::findOrFail($request->id);
        $view = view('backEnd.user.view')->with(compact('roles', 'target'))->render();
        return response()->json(['data' => $view]);
    }

    public function transaction(Request $request) {

        $users = User::join('user_roles', 'user_roles.id', 'users.role_id')->select(DB::raw("CONCAT(users.name,' (',user_roles.role_name,')') as name"), 'users.id as id')->pluck('name', 'id')->toArray();
        $bankAccountArr = BankAccount::select(DB::raw("CONCAT(bank_name,' => ',account_name,' (',account_no,')') as bank_account"), 'id')->pluck('bank_account', 'id')->toArray();
        $issues = Issue::pluck('issue_title', 'id')->toArray();
        $targets = Transaction::where('user_id', $request->id)->orderBy('id', 'desc');
        $user_id = $request->id;

        if ($request->view == 'print') {
            $targets = $targets->get();

            return view('backEnd.user.print.tr_print')->with(compact('targets', 'request', 'users', 'bankAccountArr', 'issues'));
        } else if ($request->view == 'pdf') {
            $targets = $targets->get();
            $pdf = PDF::loadView('backEnd.user.print.tr_print', compact('targets', 'request', 'users', 'bankAccountArr', 'issues'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            $fileName = "transaction_list_" . date('d_m_Y_H_i_s');
            return $pdf->download("$fileName.pdf");
        } else if ($request->view == 'excel') {
            $targets = $targets->get();
            $viewFile = 'backEnd.user.print.tr_print';
            $fileName = "transaction_list_" . date('d_m_Y_H_i_s');
            $downLoadFileName = "$fileName.xlsx";
            $data['targets'] = $targets;
            $data['request'] = $request;
            $data['users'] = $users;
            $data['bankAccountArr'] = $bankAccountArr;
            $data['issues'] = $issues;
            return Excel::download(new ExcelExport($viewFile, $data), $downLoadFileName);
        }

        $targets = $targets->paginate(5);
        return view('backEnd.user.transaction')->with(compact('users', 'bankAccountArr', 'issues', 'targets', 'user_id'));
    }

    public function store(Request $request) {

        $rules = [
            'name' => 'required',
            'role_id' => 'required',
            'email' => 'required',
//            'phone' => 'required',
            'password' => 'required|min:6',
        ];

        if (!empty($request->file('profile_photo'))) {
            $rules['profile_photo'] = ['image', 'mimes:jpg,jpeg,png'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $target = new User;
        $target->name = $request->name;
        $target->role_id = $request->role_id;
        $target->email = $request->email;
        $target->phone = $request->phone;
        $target->password = Hash::make($request->password);
        if ($files = $request->file('profile_photo')) {
            $imagePath = 'uploads/profile_photo/';
            $fileName = uniqid() . "." . date('Ymd') . "." . $files->getClientOriginalExtension();
            $dbName = $imagePath . '' . $fileName;
            $files->move(public_path($imagePath), $fileName);
            $target->profile_photo = $dbName;
        }
        $target->address = $request->address;
        if ($target->save()) {
            return response()->json(['response' => 'success', 'id' => $target->id, 'name' => $target->name]);
        }
    }

    public function edit(Request $request) {
        $target = User::findOrFail($request->id);
        $roles = ['' => __('lang.SELECT_ROLE')] + UserRole::pluck('role_name', 'id')->toArray();
        $view = view('backEnd.user.edit')->with(compact('target', 'roles'))->render();
        return response()->json(['data' => $view]);
    }

    public function update(Request $request) {
//        echo "<pre>";print_r(public_path());exit;
        $rules = [
            'name' => 'required',
            'role_id' => 'required',
            'email' => 'required',
//            'phone' => 'required',
        ];
        if (!empty($request->file('profile_photo'))) {
            $rules['profile_photo'] = ['image', 'mimes:jpg,jpeg,png'];
        }
        if (!empty($request->password)) {
            $rules['password'] = ['required', 'min:6'];
        }
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $target = User::findOrFail($request->id);
        $target->name = $request->name;
        $target->role_id = $request->role_id;
        $target->email = $request->email;
        $target->phone = $request->phone;
        if (!empty($request->password)) {
            $target->password = Hash::make($request->password);
        }
        if ($files = $request->file('profile_photo')) {
            if (file_exists(public_path() . '/' . $target->profile_photo) && !empty($target->profile_photo)) {
                unlink(public_path() . '/' . $target->profile_photo);
            }

            $imagePath = 'uploads/profile_photo/';
            $fileName = uniqid() . "." . date('Ymd') . "." . $files->getClientOriginalExtension();
            $dbName = $imagePath . '' . $fileName;
            $files->move(public_path($imagePath), $fileName);
            $target->profile_photo = $dbName;
        }
        $target->address = $request->address;
        if ($target->save()) {
            return response()->json(['response' => 'success']);
        }
    }

    public function destroy(Request $request) {
        $target = User::findOrFail($request->id);
        if ($target->delete()) {
            if (file_exists(public_path() . '/' . $target->profile_photo) && !empty($target->profile_photo)) {
                unlink(public_path() . '/' . $target->profile_photo);
            }
            Session::flash('success', __('lang.USER_DELETED_SUCCESSFULLY'));
//            return redirect()->route('userRole.index', ['page' => $request->get('page', 1)]);
            return redirect()->route('user.index');
        }
    }

    public function filter(Request $request) {
        $url = 'user_id=' . $request->user_id . '&role_id=' . $request->role_id . '&search_value=' . $request->search_value;
        return redirect('admin/user?' . $url);
    }

    public function recablelist() {
        $this->db->from('suppliers');
        $this->db->group_by('id');
        $query = $this->db->get();
        $results = $query->result();


        $i = 0;
        $sdue = 0;
        foreach ($results as $key => $value) {
            $i++;
            $Purchases = array();
            $supplier_id = $value->id;
            $opening_blance = $value->opening_blance;

            // purchases //

            $resultsPurchases = $this->getAcRecieveablePurchases($supplier_id);
            //$queryPurchases->row();
            // adv payment
            $this->db->select('SUM(adv_amount) as adv_amount ');
            $this->db->from('adv_payment');
            $this->db->where('suppliers_id', $supplier_id);
            $queryadv = $this->db->get();
            $advresults = $queryadv->row();

            if (isset($advresults->adv_amount)) {
                $advAmount = $advresults->adv_amount;
            } else {
                $advAmount = 0;
            }

            $marge = $this->checkMarger('supplier_id', $supplier_id);
            $tdue = (-$resultsPurchases->tdue) + ($advAmount);
            $tpaid = $resultsPurchases->tpaid + $advAmount;
            $sdue = $tdue - $opening_blance;
            $Purchases['id'] = $value->id;
            $Purchases['cname'] = $value->name;
            $Purchases['cphone'] = $value->phone;
            $Purchases['cid'] = $resultsPurchases->sid;
            $Purchases['gtotal'] = $resultsPurchases->gtotal + $opening_blance;
            $Purchases['tpaid'] = $tpaid;
            $Purchases['due'] = $tdue - $opening_blance;
            $Purchases['marge_id'] = $marge;
            $Purchases['type'] = 'supplier';
            if (($sdue < 0) || ($sdue == 0)) {
                unset($Purchases);
            } else {
                //if(($Purchases['marge_id'] !='') && ($Purchases['due'] >= $Purchases['tpaid'])){
                //unset($Purchases); 
                //}else{
                $resultsOut[] = $Purchases;
                if ($marge != '') {
                    $arrayMarge[] = $marge;
                }
                //}            	
            }
        }
        $this->db->from('customers');
        $this->db->group_by('id');
        $query = $this->db->get();
        $cusResults = $query->result();



        foreach ($cusResults as $key => $value) {
            $Customar = array();
            $customer_id = $value->id;
            $opening_blance = $value->opening_blance;

            // Sales //

            $resultsPayments = $this->getAcRecieveableSale2($customer_id);
            //$queryPayments->row();   
            $resultsCustomar = $this->getAcRecieveableSale($customer_id);
            //$queryPurchases->row();  

            $this->db->select('payment_date ,sum(payment_amount) as cAmount');
            $this->db->from('today_collection');
            $this->db->where('customer_id', $customer_id);
            $this->db->where('payment_date >=', '2016-12-31 23:59:59');
            $queryCollection = $this->db->get();
            $resultsCollection = $queryCollection->row();

            // adv collection 

            $this->db->select('SUM(adv_collection) as adv_collection,add_date ');
            $this->db->from('adv_collection');
            $this->db->where('adv_collection !=', '0');
            $this->db->where('adv_collection.add_date  <=', '2016-12-31 23:59:59');
            $this->db->where('customer_id', $customer_id);
            $queryadv = $this->db->get();
            $advresults = $queryadv->row();

            // marge
            $marge = $this->checkMarger('customer_id', $customer_id);

            $tColleAcounts = $resultsCollection->cAmount + $resultsPayments->pAmount + $advresults->adv_collection;

            $tdues = $resultsCustomar->gtotal - $tColleAcounts;

            $Customar['id'] = $value->id;
            $Customar['cname'] = $value->name;
            $Customar['cphone'] = $value->phone;
            $Customar['cid'] = $resultsCustomar->cid;
            $Customar['gtotal'] = $resultsCustomar->gtotal + $opening_blance;
            $Customar['tpaid'] = $tColleAcounts;
            $Customar['due'] = $tdues + $opening_blance;
            $Customar['marge_id'] = $marge;
            $Customar['type'] = 'customer';



            if (($Customar['due'] < 0) || ($Customar['due'] == 0)) {
                unset($Customar);
            } else {
                if (in_array($marge, $arrayMarge)) {
                    
                } else {
                    $resultsOut[] = $Customar;
                    return $resultsOut;
                }
            }
        }

        return $resultsOut;
    }

}
