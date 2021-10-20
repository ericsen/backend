<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Routing\UrlGenerator;
// use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        if(env('REDIRECT_HTTPS'))
        {
            $url->forceScheme('https');
        }
        $uri = $this->app->request->getRequestUri();
        //
        DB::listen(function($query) use($uri){
            if(stripos($query->sql, 'insert into') !== false || stripos($query->sql, 'delete from') !== false || stripos($query->sql, 'update') !== false){
                File::append(
                    storage_path('/logs/query_'.date('Ymd').'.log'),
                    "[".date('Y-m-d H:i:s')."]"."[{$uri}]". $query->sql . ' [' . implode(', ', $query->bindings) . ']' . PHP_EOL
                );
            }
            
            // Log::info(
            //     $query->sql,
            //     $query->bindings,
            //     $query->time
            // );
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
