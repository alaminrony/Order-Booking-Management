<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\RoleToAccess;
use Auth;

class AppServiceProvider extends ServiceProvider {

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        view()->composer('*', function ($view) {

            //get request notification number on topnavber in all views
            if (Auth::check()) {
                $roleToAccess = RoleToAccess::where('role_id',Auth::user()->role_id)->get();
                if($roleToAccess->isNotEmpty()){
                    $accessArr = [];
                    $i = 0;
                    foreach($roleToAccess as $access){
                        $accessArr[$access->module_id][$i] = $access->module_operation_id;
                        $i++;
                    }
                }
                $view->with([
                    'accessArr' => $accessArr,
                ]);
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        //
    }

}
