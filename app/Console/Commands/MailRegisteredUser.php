<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Modules\Customer\Customer;
use Lang;
use Mail;

class MailRegisteredUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail_registered_user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mail Registered User';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
		//get customer
		$customers = Customer::whereRaw("is_active = 0 and DATE(created_at)='".date("Y-m-d")."'")->get();
		if($customers) {
			foreach($customers as $key => $customer) {
			  /*sent email*/
			  if(!empty($customer->email)) {
                  Mail::send('emails.register_user', array('customer' => $customer), function ($message) use ($customer) {
                      $message->from('no-reply@luthansa.co.id', 'Register User, Luthansa Group, Tour & Transport');
                      $message->to($customer->email);
                      $message->subject('Register User, Luthansa Group, Tour & Transport');
                  });
              }
              
			  //update customer
			  Customer::where(['id' => $customer->id])
			  ->update(['is_active' => 1,'password_decrypt' => '','remember_token' => ""]);
		  
			}
		}		
    }
}
