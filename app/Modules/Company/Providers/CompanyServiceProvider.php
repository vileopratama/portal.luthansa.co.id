<?php
namespace App\Modules\Company\Providers;

use App;
use Config;
use Lang;
use View;
use Illuminate\Support\ServiceProvider;

class CompanyServiceProvider extends ServiceProvider
{
	/**
	 * Register the Company module service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your modules
		// services in the IoC container. If you wish, you may make additional
		// methods or service providers to keep the code more focused and granular.
		App::register('App\Modules\Company\Providers\RouteServiceProvider');

		$this->registerNamespaces();
	}

	/**
	 * Register the Company module resource namespaces.
	 *
	 * @return void
	 */
	protected function registerNamespaces()
	{
		Lang::addNamespace('company', realpath(__DIR__.'/../Resources/Lang'));

		View::addNamespace('company', base_path('resources/views/vendor/company'));
		View::addNamespace('company', realpath(__DIR__.'/../Resources/Views'));
	}
}
