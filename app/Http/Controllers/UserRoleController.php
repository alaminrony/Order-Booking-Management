<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;

class UserRoleController extends Controller {

    public function index(Request $request) {
//        echo "<pre>";print_r($request->all());exit;

        $roles = ['' => __('lang.SELECT_ROLE')] + UserRole::pluck('role_name', 'id')->toArray();

        $targets = UserRole::orderBy('id', 'asc');
        if (!empty($request->role_id)) {
            $targets = $targets->where('id', $request->role_id);
        }

        $targets = $targets->paginate(5);

        $data['title'] = 'User Role';
        $data['meta_tag'] = 'User Role page, rafiq & sons';
        $data['meta_description'] = 'User Role rafiq & sons';
        return view('backEnd.userRole.index')->with(compact('targets', 'roles','data'));
    }

    public function create(Request $request) {
        $view = view('backEnd.userRole.createRole')->render();
        return response()->json(['data' => $view]);
    }

    public function store(Request $request) {
        $rules = [
            'role_name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $target = new UserRole;
        $target->role_name = $request->role_name;
        if ($target->save()) {
            return response()->json(['response' => 'success']);
        }
    }

    public function edit(Request $request) {
        $target = UserRole::findOrFail($request->id);
        $view = view('backEnd.userRole.editRole')->with(compact('target'))->render();
        return response()->json(['data' => $view]);
    }

    public function update(Request $request) {
//        echo "<pre>";print_r($request->all());exit;
        $rules = [
            'role_name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $target = UserRole::findOrFail($request->id);
        $target->role_name = $request->role_name;
        if ($target->save()) {
            return response()->json(['response' => 'success']);
        }
    }

    public function destroy(Request $request) {
        $target = UserRole::findOrFail($request->id);
        if ($target->delete()) {
            Session::flash('success', __('lang.ROLE_DELETED_SUCCESSFULLY'));
//            return redirect()->route('userRole.index', ['page' => $request->get('page', 1)]);
            return redirect()->route('role.index');
        }
    }

    public function filter(Request $request) {
        $url = 'role_id=' . $request->role_id;
        return redirect('admin/user-role?' . $url);
    }

}
