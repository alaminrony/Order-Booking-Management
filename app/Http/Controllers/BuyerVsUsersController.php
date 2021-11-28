<?php

namespace App\Http\Controllers;

use App\Models\BuyerVsUser;
use App\Models\User;
use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;
use DB;

class BuyerVsUsersController extends Controller {

    public function index(Request $request) {
        $targets = BuyerVsUser::orderBy('id', 'desc');

        if (!empty($request->search_value)) {
            $searchText = $request->search_value;
            $targets->where(function ($query) use ($searchText) {
                $query->where('dep_name', 'like', "%{$searchText}%");
            });
        }

        $targets = $targets->paginate(5);

        $buyers = ['' => '--Select Buyer--'] + Buyer::pluck('name', 'id')->toArray();
        $users = ['' => '--Select Users--'] + User::join('roles', 'roles.id', '=', 'users.role_id')->select(DB::raw("CONCAT(users.name,' (',roles.role_name,')') as name"), 'users.id as user_id')->pluck('name', 'user_id')->toArray();

        $data['title'] = 'BuyerVsUser List';
        $data['meta_tag'] = 'BuyerVsUser page';
        $data['meta_description'] = 'BuyerVsUser';
        return view('backEnd.buyer_vs_users.index')->with(compact('targets', 'data', 'buyers', 'users'));
//        echo "<pre>";print_r($target->toArray());exit;
    }

    public function create(Request $request) {
        $buyers = ['' => '--Select Buyer--'] + Buyer::pluck('name', 'id')->toArray();
        $users = ['' => '--Select Users--'] + User::join('roles', 'roles.id', '=', 'users.role_id')->select(DB::raw("CONCAT(users.name,' (',roles.role_name,')') as name"), 'users.id as user_id')->pluck('name', 'user_id')->toArray();
        $view = view('backEnd.buyer_vs_users.createBuyerVsUser')->with(compact('buyers', 'users'))->render();
        return response()->json(['data' => $view]);
    }

    public function store(Request $request) {
        $rules = [
            'buyer_id' => 'required',
            'user_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $target = new BuyerVsUser;
        $target->buyer_id = $request->buyer_id;
        $target->user_id = $request->user_id;
        if ($target->save()) {
            return response()->json(['response' => 'success']);
        }
    }

    public function edit(Request $request) {
        $target = BuyerVsUser::findOrFail($request->id);
        $buyers = ['' => '--Select Buyer--'] + Buyer::pluck('name', 'id')->toArray();
        $users = ['' => '--Select Users--'] + User::join('roles', 'roles.id', '=', 'users.role_id')->select(DB::raw("CONCAT(users.name,' (',roles.role_name,')') as name"), 'users.id as user_id')->pluck('name', 'user_id')->toArray();
        $view = view('backEnd.buyer_vs_users.editBuyerVsUser')->with(compact('target', 'buyers', 'users'))->render();
        return response()->json(['data' => $view]);
    }

    public function update(Request $request) {
//        echo "<pre>";print_r($request->all());exit;
        $rules = [
            'buyer_id' => 'required',
            'user_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $target = BuyerVsUser::findOrFail($request->id);
        $target->buyer_id = $request->buyer_id;
        $target->user_id = $request->user_id;
        if ($target->save()) {
            return response()->json(['response' => 'success']);
        }
    }

    public function destroy(Request $request) {
        $target = BuyerVsUser::findOrFail($request->id);
//        echo "<pre>";print_r($target->toArray());exit;
        if ($target->delete()) {
            Session::flash('success', __('lang.BUYER_VS_USERS_DELETED_SUCCESSFULLY'));
//            return redirect()->route('head.index', ['page' => $request->get('page', 1)]);
            return redirect()->route('buyerVsUsers.index');
        }
    }

    public function filter(Request $request) {
        $url = '&search_value=' . $request->search_value;
        return redirect('admin/buyerVsUsers-list?' . $url);
    }

}
