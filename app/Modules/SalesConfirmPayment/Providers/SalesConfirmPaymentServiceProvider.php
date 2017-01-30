<?php
namespace App\Modules\SalesConfirmPayment\Providers;

use App;
use Config;
use Lang;
use View;
use Illuminate\Support\ServiceProvider;

class SalesConfirmPaymentServiceProvider extends ServiceProvider
{
	/**
	 * Register the Sales Order Confirm Payment module service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your modules
		// services in the IoC container. If you wish, you may make additional
		// methods or service providers to keep the code more focused and granular.
		App::register('App\Modules\SalesConfirmPayment\Providers\RouteServiceProvider');

		$this->registerNamespaces();
	}

	/**
	 * Register the Sales Order Confirm Payment module resource namespaces.
	 *
	 * @return void
	 */
	protected function registerNamespaces()
	{
		Lang::addNamespace('sales-confirm-payment', realpath(__DIR__.'/../Resources/Lang'));

		View::addNamespace('sales-confirm-payment', base_path('resources/views/vendor/sales-confirm-payment'));
		View::addNamespace('sales-confirm-payment', realpath(__DIR__.'/../Resources/Views'));
	}
}
