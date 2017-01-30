<?php
namespace App\Modules\ReportIncomeExpense\Providers;

use App;
use Config;
use Lang;
use View;
use Illuminate\Support\ServiceProvider;

class ReportIncomeExpenseServiceProvider extends ServiceProvider
{
	/**
	 * Register the Report Income Expense module service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your modules
		// services in the IoC container. If you wish, you may make additional
		// methods or service providers to keep the code more focused and granular.
		App::register('App\Modules\ReportIncomeExpense\Providers\RouteServiceProvider');

		$this->registerNamespaces();
	}

	/**
	 * Register the Report Income Expense module resource namespaces.
	 *
	 * @return void
	 */
	protected function registerNamespaces()
	{
		Lang::addNamespace('report-income-expense', realpath(__DIR__.'/../Resources/Lang'));

		View::addNamespace('report-income-expense', base_path('resources/views/vendor/report-income-expense'));
		View::addNamespace('report-income-expense', realpath(__DIR__.'/../Resources/Views'));
	}
}
