<?php
namespace App\Modules\SalesSpj\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SalesSpjDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		// $this->call('App\Modules\SalesSpj\Database\Seeds\FoobarTableSeeder');
	}

}
