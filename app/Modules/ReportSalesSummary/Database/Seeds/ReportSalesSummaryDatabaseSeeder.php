<?php
namespace App\Modules\ReportSalesSummary\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ReportSalesSummaryDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		// $this->call('App\Modules\ReportSalesSummary\Database\Seeds\FoobarTableSeeder');
	}

}
