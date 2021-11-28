<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\UserRole;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;

class SettingController extends Controller {

    public function __construct() {
        Validator::extend('without_spaces', function($attr, $value) {
            return preg_match('/^\S*$/u', $value);
        });
    }

    public function index(Request $request) {
//        echo "<pre>";print_r($request->all());exit;

        $targets = Setting::orderBy('id', 'desc');

        if (!empty($request->search_value)) {
            $searchText = $request->search_value;
            $targets->where(function ($query) use ($searchText) {
                $query->where('key_name', 'like', "%{$searchText}%")
                        ->orWhere('key_value', 'like', "%{$searchText}%");
            });
        }

        $targets = $targets->paginate(5);
        
        $data['title'] = 'Setting';
        $data['meta_tag'] = 'Setting, rafiq & sons';
        $data['meta_description'] = 'Setting, rafiq & sons';
        return view('backEnd.setting.index')->with(compact('targets','data'));
//        echo "<pre>";print_r($target->toArray());exit;
    }

    public function create(Request $request) {
        $view = view('backEnd.setting.createSetting')->render();
        return response()->json(['data' => $view]);
    }

    public function store(Request $request) {
        $rules = [
            'key_name' => 'required|without_spaces',
        ];

        $message = [
            'key_name.without_spaces' => 'Key not allowed blank spaces',
        ];

        if (!empty($request->file('image'))) {
            $rules['image'] = ['image', 'mimes:jpg,jpeg,png'];
        }

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $target = new Setting;
        $target->key_name = $request->key_name;
        $target->key_value = $request->key_value;
        if ($files = $request->file('image')) {
            $imagePath = 'uploads/setting/';
            $fileName = uniqid() . "." . date('Ymd') . "." . $files->getClientOriginalExtension();
            $dbName = $imagePath . '' . $fileName;
            $files->move(public_path($imagePath), $fileName);
            $target->image = $dbName;
        }
        if ($target->save()) {
            return response()->json(['response' => 'success']);
        }
    }

    public function edit(Request $request) {
        $target = Setting::findOrFail($request->id);
        $view = view('backEnd.setting.editSetting')->with(compact('target'))->render();
        return response()->json(['data' => $view]);
    }

    public function update(Request $request) {
//        echo "<pre>";print_r($request->all());exit;
        $rules = [
            'key_name' => 'required|without_spaces',
        ];

        $message = [
            'key_name.without_spaces' => 'Key not allowed blank spaces',
        ];

        if (!empty($request->file('image'))) {
            $rules['image'] = ['image', 'mimes:jpg,jpeg,png'];
        }

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }


        $target = Setting::findOrFail($request->id);
        $target->key_name = $request->key_name;
        $target->key_value = $request->key_value;
        if ($files = $request->file('image')) {
            if (file_exists(public_path() . '/' . $target->image) && !empty($target->image)) {
                unlink(public_path() . '/' . $target->image);
            }

            $imagePath = 'uploads/setting/';
            $fileName = uniqid() . "." . date('Ymd') . "." . $files->getClientOriginalExtension();
            $dbName = $imagePath . '' . $fileName;
            $files->move(public_path($imagePath), $fileName);
            $target->image = $dbName;
        }
        if ($target->save()) {
            return response()->json(['response' => 'success']);
        }
    }

    public function destroy(Request $request) {
        $target = Setting::findOrFail($request->id);
        if ($target->delete()) {
            if (file_exists(public_path() . '/' . $target->image) && !empty($target->image)) {
                unlink(public_path() . '/' . $target->image);
            }
            Session::flash('success', __('lang.SETTING_DELETED_SUCCESSFULLY'));
//            return redirect()->route('setting.index', ['page' => $request->get('page', 1)]);
            return redirect()->route('setting.index');
        }
    }

    public function filter(Request $request) {

        $url = 'search_value=' . $request->search_value;
        return redirect('admin/setting?' . $url);
    }

}
