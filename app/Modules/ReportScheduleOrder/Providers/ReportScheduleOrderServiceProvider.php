<?php
namespace App\Modules\ReportScheduleOrder\Providers;

use App;
use Config;
use Lang;
use View;
use Illuminate\Support\ServiceProvider;

class ReportScheduleOrderServiceProvider extends ServiceProvider
{
	/**
	 * Register the Report Schedule Order module service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your modules
		// services in the IoC container. If you wish, you may make additional
		// methods or service providers to keep the code more focused and granular.
		App::register('App\Modules\ReportScheduleOrder\Providers\RouteServiceProvider');

		$this->registerNamespaces();
	}

	/**
	 * Register the Report Schedule Order module resource namespaces.
	 *
	 * @return void
	 */
	protected function registerNamespaces()
	{
		Lang::addNamespace('report-schedule-order', realpath(__DIR__.'/../Resources/Lang'));

		View::addNamespace('report-schedule-order', base_path('resources/views/vendor/report-schedule-order'));
		View::addNamespace('report-schedule-order', realpath(__DIR__.'/../Resources/Views'));
	}
}
