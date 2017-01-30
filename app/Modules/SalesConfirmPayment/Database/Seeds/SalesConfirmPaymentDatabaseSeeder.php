<?php
namespace App\Modules\SalesConfirmPayment\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SalesConfirmPaymentDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		// $this->call('App\Modules\SalesConfirmPayment\Database\Seeds\FoobarTableSeeder');
	}

}
