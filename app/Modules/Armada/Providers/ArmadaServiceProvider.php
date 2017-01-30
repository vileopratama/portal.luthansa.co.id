<?php
namespace App\Modules\Armada\Providers;

use App;
use Config;
use Lang;
use View;
use Illuminate\Support\ServiceProvider;

class ArmadaServiceProvider extends ServiceProvider
{
	/**
	 * Register the Armada module service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your modules
		// services in the IoC container. If you wish, you may make additional
		// methods or service providers to keep the code more focused and granular.
		App::register('App\Modules\Armada\Providers\RouteServiceProvider');

		$this->registerNamespaces();
	}

	/**
	 * Register the Armada module resource namespaces.
	 *
	 * @return void
	 */
	protected function registerNamespaces()
	{
		Lang::addNamespace('armada', realpath(__DIR__.'/../Resources/Lang'));

		View::addNamespace('armada', base_path('resources/views/vendor/armada'));
		View::addNamespace('armada', realpath(__DIR__.'/../Resources/Views'));
	}
}
