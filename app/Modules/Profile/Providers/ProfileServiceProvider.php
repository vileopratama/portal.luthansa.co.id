<?php
namespace App\Modules\Profile\Providers;

use App;
use Config;
use Lang;
use View;
use Illuminate\Support\ServiceProvider;

class ProfileServiceProvider extends ServiceProvider
{
	/**
	 * Register the Profile module service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your modules
		// services in the IoC container. If you wish, you may make additional
		// methods or service providers to keep the code more focused and granular.
		App::register('App\Modules\Profile\Providers\RouteServiceProvider');

		$this->registerNamespaces();
	}

	/**
	 * Register the Profile module resource namespaces.
	 *
	 * @return void
	 */
	protected function registerNamespaces()
	{
		Lang::addNamespace('profile', realpath(__DIR__.'/../Resources/Lang'));

		View::addNamespace('profile', base_path('resources/views/vendor/profile'));
		View::addNamespace('profile', realpath(__DIR__.'/../Resources/Views'));
	}
}
