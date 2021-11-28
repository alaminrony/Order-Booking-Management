<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\RoleToAccess;
use App\Models\Setting;
use DB;
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
//                $settings = Setting::pluck('key_value','key_name')
//                        ->toArray();
            $settings = Setting::get();
            $settingArr = [];
            if ($settings->isNotEmpty()) {
                foreach ($settings as $setting) {
                    if (!empty($setting->image)) {
                        $settingArr[$setting->key_name] = $setting->image;
                    }else{
                       $settingArr[$setting->key_name] = $setting->key_value; 
                    }
                }
            }
          
            $view->with([
                'settingArr' => $settingArr,
            ]);
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
