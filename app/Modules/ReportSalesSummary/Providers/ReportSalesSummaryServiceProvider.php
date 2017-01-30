<?php
namespace App\Modules\ReportSalesSummary\Providers;

use App;
use Config;
use Lang;
use View;
use Illuminate\Support\ServiceProvider;

class ReportSalesSummaryServiceProvider extends ServiceProvider
{
	/**
	 * Register the Report Sales Summary module service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your modules
		// services in the IoC container. If you wish, you may make additional
		// methods or service providers to keep the code more focused and granular.
		App::register('App\Modules\ReportSalesSummary\Providers\RouteServiceProvider');

		$this->registerNamespaces();
	}

	/**
	 * Register the Report Sales Summary module resource namespaces.
	 *
	 * @return void
	 */
	protected function registerNamespaces()
	{
		Lang::addNamespace('report-sales-summary', realpath(__DIR__.'/../Resources/Lang'));

		View::addNamespace('report-sales-summary', base_path('resources/views/vendor/report-sales-summary'));
		View::addNamespace('report-sales-summary', realpath(__DIR__.'/../Resources/Views'));
	}
}
