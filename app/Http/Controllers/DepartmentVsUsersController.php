<?php

namespace App\Http\Controllers;

use App\Models\DepartmentVsUser;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;
use DB;

class DepartmentVsUsersController extends Controller {

    public function index(Request $request) {
        $targets = DepartmentVsUser::orderBy('id', 'desc');

        if (!empty($request->search_value)) {
            $searchText = $request->search_value;
            $targets->where(function ($query) use ($searchText) {
                $query->where('dep_name', 'like', "%{$searchText}%");
            });
        }

        $targets = $targets->paginate(20);

        $departments = ['' => '--Select Department--'] + Department::pluck('dep_name', 'id')->toArray();
        $users = ['' => '--Select Users--'] + User::join('roles', 'roles.id', '=', 'users.role_id')->select(DB::raw("CONCAT(users.name,' (',roles.role_name,')') as name"), 'users.id as user_id')->pluck('name', 'user_id')->toArray();
        $users_email = User::pluck('email', 'id')->toArray();

        $data['title'] = 'DepartmentVsUser List';
        $data['meta_tag'] = 'DepartmentVsUser page';
        $data['meta_description'] = 'DepartmentVsUser';
        return view('backEnd.department_vs_users.index')->with(compact('targets', 'data', 'departments', 'users','users_email'));
//        echo "<pre>";print_r($target->toArray());exit;
    }

    public function create(Request $request) {
        $departments = ['' => '--Select Department--'] + Department::pluck('dep_name', 'id')->toArray();
        $users = ['' => '--Select Users--'] + User::join('roles', 'roles.id', '=', 'users.role_id')->select(DB::raw("CONCAT(users.name,' (',roles.role_name,')') as name"), 'users.id as user_id')->pluck('name', 'user_id')->toArray();
        $view = view('backEnd.department_vs_users.createDepartmentVsUser')->with(compact('departments', 'users'))->render();
        return response()->json(['data' => $view]);
    }

    public function store(Request $request) {
        $rules = [
            'department_id' => 'required',
            'user_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $target = new DepartmentVsUser;
        $target->department_id = $request->department_id;
        $target->user_id = $request->user_id;
        if ($target->save()) {
            return response()->json(['response' => 'success']);
        }
    }

    public function edit(Request $request) {
        $target = DepartmentVsUser::findOrFail($request->id);
        $departments = ['' => '--Select Department--'] + Department::pluck('dep_name', 'id')->toArray();
        $users = ['' => '--Select Users--'] + User::join('roles', 'roles.id', '=', 'users.role_id')->select(DB::raw("CONCAT(users.name,' (',roles.role_name,')') as name"), 'users.id as user_id')->pluck('name', 'user_id')->toArray();
        $view = view('backEnd.department_vs_users.editDepartmentVsUser')->with(compact('target', 'departments', 'users'))->render();
        return response()->json(['data' => $view]);
    }

    public function update(Request $request) {
//        echo "<pre>";print_r($request->all());exit;
        $rules = [
            'department_id' => 'required',
            'user_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $target = DepartmentVsUser::findOrFail($request->id);
        $target->department_id = $request->department_id;
        $target->user_id = $request->user_id;
        if ($target->save()) {
            return response()->json(['response' => 'success']);
        }
    }

    public function destroy(Request $request) {
        $target = DepartmentVsUser::findOrFail($request->id);
        if ($target->delete()) {
            Session::flash('success', __('lang.DEPARTMENT_VS_USER_DELETED_SUCCESSFULLY'));
//            return redirect()->route('head.index', ['page' => $request->get('page', 1)]);
            return redirect()->route('departmentVsUsers.index');
        }
    }

    public function filter(Request $request) {
        $url = 'transaction_type=' . $request->transaction_type . '&search_value=' . $request->search_value;
        return redirect('admin/head-list?' . $url);
    }

}
