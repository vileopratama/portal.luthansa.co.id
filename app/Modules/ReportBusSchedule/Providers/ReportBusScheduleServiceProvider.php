<?php
namespace App\Modules\ReportBusSchedule\Providers;

use App;
use Config;
use Lang;
use View;
use Illuminate\Support\ServiceProvider;

class ReportBusScheduleServiceProvider extends ServiceProvider
{
	/**
	 * Register the Report Bus Schedule module service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your modules
		// services in the IoC container. If you wish, you may make additional
		// methods or service providers to keep the code more focused and granular.
		App::register('App\Modules\ReportBusSchedule\Providers\RouteServiceProvider');

		$this->registerNamespaces();
	}

	/**
	 * Register the Report Bus Schedule module resource namespaces.
	 *
	 * @return void
	 */
	protected function registerNamespaces()
	{
		Lang::addNamespace('report-bus-schedule', realpath(__DIR__.'/../Resources/Lang'));

		View::addNamespace('report-bus-schedule', base_path('resources/views/vendor/report-bus-schedule'));
		View::addNamespace('report-bus-schedule', realpath(__DIR__.'/../Resources/Views'));
	}
}
