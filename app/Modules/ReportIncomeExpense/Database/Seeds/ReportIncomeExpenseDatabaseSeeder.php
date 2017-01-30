<?php
namespace App\Modules\ReportIncomeExpense\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ReportIncomeExpenseDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		// $this->call('App\Modules\ReportIncomeExpense\Database\Seeds\FoobarTableSeeder');
	}

}
