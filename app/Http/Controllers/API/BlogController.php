<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\JWTAuth;
use Mail;
use DB;


class BlogController extends Controller
{
    public function categoryList(Request $request){
        $catList = DB::table('blog_categories')->orderBy('id','desc')->get();
        if($catList){
            return response()->json(
                [
                    'status' => 'Success',
                    'message' => 'Data Retrieved Successfully',
                    'data' => $catList
                ]
                , Response::HTTP_OK);
        }
       
    //    echo "<pre>";print_r($catList->toArray());exit;
    }

    public function blogList(Request $request){
        
        $blogList = DB::table('blogs')->orderBy('id','desc');
        
        if(!empty($request->catId)){
            $blogList = $blogList->where('cat_id',$request->catId);
        }
        
        $blogList = $blogList->get();

        // echo "<pre>";print_r($blogList->toArray());exit;
        if($blogList){
            return response()->json(
                [
                    'status' => 'Success',
                    'message' => 'Data Retrieved Successfully',
                    'data' => $blogList
                ]
                , Response::HTTP_OK);
        }
    }


    public function blogDetails(Request $request){
        
        $blogDetails = DB::table('blogs')->where('id',$request->id)->first();

        if($blogDetails){
            return response()->json(
                [
                    'status' => 'Success',
                    'message' => 'Data Retrieved Successfully',
                    'data' => $blogDetails
                ]
                , Response::HTTP_OK);
        }

        return response()->json(
            [
                'status' => 'error',
                'message' => 'No Data found',
            ]);
       
    //    echo "<pre>";print_r($catList->toArray());exit;
    }
}
