<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;


class BuyerController extends Controller {

    public function index(Request $request) {
        $targets = Buyer::orderBy('id', 'desc');


        if (!empty($request->search_value)) {
            $searchText = $request->search_value;
            $targets->where(function ($query) use ($searchText) {
                $query->where('name', 'like', "%{$searchText}%");
            });
        }

        $targets    =   $targets->paginate(5);

        $data['title'] = 'Buyer List';
        $data['meta_tag'] = 'Buyer, IDs';
        $data['meta_description'] = 'Issue, IDS';
        return view('backEnd.buyer.index')->with(compact('targets','data'));

//        echo "<pre>";print_r($target->toArray());exit;
    }

    public function create(Request $request) {

        $view = view('backEnd.buyer.createBuyer')->render();
        return response()->json(['data' => $view]);
    }

    public function store(Request $request) {
        $rules = [
            'name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $target = new Buyer;
        $target->name = $request->name;
        $target->phone = $request->phone;
        $target->email = $request->email;
        if ($target->save()) {
            return response()->json(['response' => 'success']);
        }
    }

    public function edit(Request $request) {
        $target = Buyer::findOrFail($request->id);
        $view = view('backEnd.buyer.editBuyer')->with(compact('target'))->render();
        return response()->json(['data' => $view]);
    }

    public function update(Request $request) {
//        echo "<pre>";print_r($request->all());exit;
        $rules = [
            'name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $target = Buyer::findOrFail($request->id);
        $target->name = $request->name;
        $target->phone = $request->phone;
        $target->email = $request->email;
        if ($target->save()) {
            return response()->json(['response' => 'success']);
        }
    }

    public function destroy(Request $request) {
        $target = Buyer::findOrFail($request->id);
        if ($target->delete()) {
            Session::flash('success', __('lang.BUYER_DELETED_SUCCESSFULLY'));
//            return redirect()->route('head.index', ['page' => $request->get('page', 1)]);
            return redirect()->route('buyer.index');
        }
    }

    public function filter(Request $request) {
        $url = '&search_value=' . $request->search_value;
        return redirect('admin/buyer-list?' . $url);
    }

}
