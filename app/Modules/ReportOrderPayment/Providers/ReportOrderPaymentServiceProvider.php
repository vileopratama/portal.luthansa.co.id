<?php
namespace App\Modules\ReportOrderPayment\Providers;

use App;
use Config;
use Lang;
use View;
use Illuminate\Support\ServiceProvider;

class ReportOrderPaymentServiceProvider extends ServiceProvider
{
	/**
	 * Register the Report Order Payment module service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your modules
		// services in the IoC container. If you wish, you may make additional
		// methods or service providers to keep the code more focused and granular.
		App::register('App\Modules\ReportOrderPayment\Providers\RouteServiceProvider');

		$this->registerNamespaces();
	}

	/**
	 * Register the Report Order Payment module resource namespaces.
	 *
	 * @return void
	 */
	protected function registerNamespaces()
	{
		Lang::addNamespace('report-order-payment', realpath(__DIR__.'/../Resources/Lang'));

		View::addNamespace('report-order-payment', base_path('resources/views/vendor/report-order-payment'));
		View::addNamespace('report-order-payment', realpath(__DIR__.'/../Resources/Views'));
	}
}
