<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;

class DepartmentController extends Controller {

    public function index(Request $request) {
        $targets = Department::orderBy('id', 'desc');

        if (!empty($request->search_value)) {
            $searchText = $request->search_value;
            $targets->where(function ($query) use ($searchText) {
                $query->where('dep_name', 'like', "%{$searchText}%");
            });
        }

        $targets = $targets->paginate(5);

        $data['title'] = 'Department List';
        $data['meta_tag'] = 'Department page';
        $data['meta_description'] = 'Department';
        return view('backEnd.department.index')->with(compact('targets', 'data'));
//        echo "<pre>";print_r($target->toArray());exit;
    }

    public function create(Request $request) {
//        echo "<pre>";print_r($request->all());exit;
        $view = view('backEnd.department.createDepartment')->render();
        return response()->json(['data' => $view]);
    }

    public function store(Request $request) {
        $rules = [
            'dep_name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $target = new Department;
        $target->dep_name = $request->dep_name;
        if ($target->save()) {
            return response()->json(['response' => 'success']);
        }
    }

    public function edit(Request $request) {
        $target = Department::findOrFail($request->id);
        $view = view('backEnd.department.editDepartment')->with(compact('target'))->render();
        return response()->json(['data' => $view]);
    }

    public function update(Request $request) {
//        echo "<pre>";print_r($request->all());exit;
        $rules = [
            'dep_name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $target = Department::findOrFail($request->id);
        $target->dep_name = $request->dep_name;
        if ($target->save()) {
            return response()->json(['response' => 'success']);
        }
    }

    public function destroy(Request $request) {
        $target = Department::findOrFail($request->id);
        if ($target->delete()) {
            Session::flash('success', __('lang.DEPARTMENT_DELETED_SUCCESSFULLY'));
//            return redirect()->route('head.index', ['page' => $request->get('page', 1)]);
            return redirect()->route('department.index');
        }
    }

    public function filter(Request $request) {
        $url = '&search_value=' . $request->search_value;
        return redirect('admin/department-list?' . $url);
    }

}
