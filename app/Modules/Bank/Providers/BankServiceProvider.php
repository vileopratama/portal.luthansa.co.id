<?php
namespace App\Modules\Bank\Providers;

use App;
use Config;
use Lang;
use View;
use Illuminate\Support\ServiceProvider;

class BankServiceProvider extends ServiceProvider
{
	/**
	 * Register the Bank module service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your modules
		// services in the IoC container. If you wish, you may make additional
		// methods or service providers to keep the code more focused and granular.
		App::register('App\Modules\Bank\Providers\RouteServiceProvider');

		$this->registerNamespaces();
	}

	/**
	 * Register the Bank module resource namespaces.
	 *
	 * @return void
	 */
	protected function registerNamespaces()
	{
		Lang::addNamespace('bank', realpath(__DIR__.'/../Resources/Lang'));

		View::addNamespace('bank', base_path('resources/views/vendor/bank'));
		View::addNamespace('bank', realpath(__DIR__.'/../Resources/Views'));
	}
}
