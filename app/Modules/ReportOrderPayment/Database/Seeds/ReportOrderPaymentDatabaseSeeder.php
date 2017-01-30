<?php
namespace App\Modules\ReportOrderPayment\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ReportOrderPaymentDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		// $this->call('App\Modules\ReportOrderPayment\Database\Seeds\FoobarTableSeeder');
	}

}
