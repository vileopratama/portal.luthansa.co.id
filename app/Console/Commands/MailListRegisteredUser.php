<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Customer\Customer;
use Lang;
use Mail;
use Setting;

class MailListRegisteredUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail_list_registered_user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mail List Registered User';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
		//get customer
		$customers = Customer::whereRaw("DATE(created_at)='".date("Y-m-d")."'")
            ->get();
        $email_to = explode(';',trim(Setting::get('invoice_email_notifications')));

        if(count($customers) > 0 && count($email_to) > 0) {
			  /*sent email*/
            Mail::send('emails.register-user-list',array('customers' => $customers),function($message) use($email_to) {
                $message->from('no-reply@luthansa.co.id','Register User Per Tgl '.date('d M Y').', Luthansa Group');
                $message->to($email_to);
                $message->subject('List Register User Per Tgl '.date('d M Y'));
            });
        }
    }
}
