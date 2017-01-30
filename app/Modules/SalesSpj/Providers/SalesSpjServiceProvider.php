<?php
namespace App\Modules\SalesSpj\Providers;

use App;
use Config;
use Lang;
use View;
use Illuminate\Support\ServiceProvider;

class SalesSpjServiceProvider extends ServiceProvider
{
	/**
	 * Register the SPJ module service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your modules
		// services in the IoC container. If you wish, you may make additional
		// methods or service providers to keep the code more focused and granular.
		App::register('App\Modules\SalesSpj\Providers\RouteServiceProvider');

		$this->registerNamespaces();
	}

	/**
	 * Register the SPJ module resource namespaces.
	 *
	 * @return void
	 */
	protected function registerNamespaces()
	{
		Lang::addNamespace('sales-spj', realpath(__DIR__.'/../Resources/Lang'));

		View::addNamespace('sales-spj', base_path('resources/views/vendor/sales-spj'));
		View::addNamespace('sales-spj', realpath(__DIR__.'/../Resources/Views'));
	}
}
