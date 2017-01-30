<?php
namespace App\Modules\Department\Providers;

use App;
use Config;
use Lang;
use View;
use Illuminate\Support\ServiceProvider;

class DepartmentServiceProvider extends ServiceProvider
{
	/**
	 * Register the Department module service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your modules
		// services in the IoC container. If you wish, you may make additional
		// methods or service providers to keep the code more focused and granular.
		App::register('App\Modules\Department\Providers\RouteServiceProvider');

		$this->registerNamespaces();
	}

	/**
	 * Register the Department module resource namespaces.
	 *
	 * @return void
	 */
	protected function registerNamespaces()
	{
		Lang::addNamespace('department', realpath(__DIR__.'/../Resources/Lang'));

		View::addNamespace('department', base_path('resources/views/vendor/department'));
		View::addNamespace('department', realpath(__DIR__.'/../Resources/Views'));
	}
}
