<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapAdminRoutes();
        $this->mapApiRoutes();
        $this->mapGameServiceRoutes();
        $this->mapWebRoutes();
        $this->mapTPGRoutes();
        $this->mapICGRoutes();
        $this->mapJCRoutes();
        $this->mapNGRoutes();
        $this->mapPDRoutes();
        $this->mapCWRoutes();
        $this->mapICGFRoutes();
        $this->mapAMEBARoutes();
        $this->mapCogudRoutes();
        $this->mapHungRoutes();
        $this->mapSUPERSPORTRoutes();
        $this->mapAVIARoutes();
        $this->mapSARoutes();
        $this->mapRTGRoutes();
        $this->mapDGRoutes();
        $this->mapMGRoutes();
        $this->resetPwdRoutes();
        $this->mapWMRoutes();
        $this->mapALLBETRoutes();
        $this->mapBACRoutes();
        $this->mapTPGFRoutes();
        $this->mapZPRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "admin" routes for the application.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::prefix('admin')
            ->middleware(['web', 'AdminLanguage'])
            ->namespace($this->namespace)
            ->group(base_path('routes/admin.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    /**
     * Define the "gameservice" routes for the application.
     *
     * @return void
     */
    protected function mapGameServiceRoutes()
    {
        Route::prefix('gameservice')
            ->middleware('gameservice')
            ->namespace($this->namespace)
            ->group(base_path('routes/gameservice.php'));
    }
	
	protected function mapTPGRoutes()
    {
        Route::prefix('api')
			->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/TPG.php'));
    }

    
    protected function mapTPGFRoutes()
    {
        Route::prefix('api')
			->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/TPGF.php'));
    }


    protected function mapAMEBARoutes()
    {
        Route::prefix('api')
			->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/AMEBA.php'));
    }

    protected function mapCogudRoutes()
    {
		Route::prefix('api')
			->middleware('api')
			->namespace($this->namespace)
			->group(base_path('routes/cogud.php'));
    }

    protected function mapHungRoutes()
    {
		Route::prefix('api')
			->middleware('api')
			->namespace($this->namespace)
			->group(base_path('routes/hung.php'));
    }
	
	protected function mapSUPERSPORTRoutes()
    {
		Route::prefix('api')
			->middleware('api')
			->namespace($this->namespace)
			->group(base_path('routes/SUPERSPORT.php'));
    }
	
	protected function mapAVIARoutes()
    {
		Route::prefix('api')
			->middleware('api')
			->namespace($this->namespace)
			->group(base_path('routes/AVIA.php'));
    }
	
	protected function mapMGRoutes()
    {
		Route::prefix('api')
			->middleware('api')
			->namespace($this->namespace)
			->group(base_path('routes/MG.php'));
    }

    protected function mapSARoutes(){
        Route::prefix('api')
			->middleware('api')
			->namespace($this->namespace)
			->group(base_path('routes/SA.php'));
    }

    protected function mapRTGRoutes(){
        Route::prefix('api')
			->middleware('api')
			->namespace($this->namespace)
			->group(base_path('routes/RTG.php'));
    }

    protected function mapICGRoutes(){
        Route::prefix('api')
			->middleware('api')
			->namespace($this->namespace)
			->group(base_path('routes/ICG.php'));
    }

    protected function mapICGFRoutes(){
        Route::prefix('api')
			->middleware('api')
			->namespace($this->namespace)
			->group(base_path('routes/ICGF.php'));
    }

    protected function mapJCRoutes(){
        Route::prefix('api')
			->middleware('api')
			->namespace($this->namespace)
			->group(base_path('routes/JC.php'));
    }
    protected function mapNGRoutes(){
        Route::prefix('api')
			->middleware('api')
			->namespace($this->namespace)
			->group(base_path('routes/NG.php'));
    }
    protected function mapPDRoutes(){
        Route::prefix('api')
			->middleware('api')
			->namespace($this->namespace)
			->group(base_path('routes/PD.php'));
    }
    protected function mapCWRoutes(){
        Route::prefix('api')
			->middleware('api')
			->namespace($this->namespace)
			->group(base_path('routes/CW.php'));
    }
 
    protected function mapDGRoutes(){
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/DG.php'));
    }

    protected function resetPwdRoutes(){
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/ResetPwd.php'));
    }

    protected function mapWMRoutes(){
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/WM.php'));
    }
	   
	protected function mapALLBETRoutes(){
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/ALLBET.php'));
    }

    protected function mapBACRoutes(){
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/BAC.php'));
    }

    protected function mapZPRoutes(){
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/ZP.php'));
    }
}
