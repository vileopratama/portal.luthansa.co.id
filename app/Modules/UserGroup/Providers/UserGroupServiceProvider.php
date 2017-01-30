<?php
namespace App\Modules\UserGroup\Providers;

use App;
use Config;
use Lang;
use View;
use Illuminate\Support\ServiceProvider;

class UserGroupServiceProvider extends ServiceProvider
{
	/**
	 * Register the User Group module service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your modules
		// services in the IoC container. If you wish, you may make additional
		// methods or service providers to keep the code more focused and granular.
		App::register('App\Modules\UserGroup\Providers\RouteServiceProvider');

		$this->registerNamespaces();
	}

	/**
	 * Register the User Group module resource namespaces.
	 *
	 * @return void
	 */
	protected function registerNamespaces()
	{
		Lang::addNamespace('user-group', realpath(__DIR__.'/../Resources/Lang'));

		View::addNamespace('user-group', base_path('resources/views/vendor/user-group'));
		View::addNamespace('user-group', realpath(__DIR__.'/../Resources/Views'));
	}
}
