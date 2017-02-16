<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Lang;
use Mail;

class Kernel extends ConsoleKernel {
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\MailRegisteredUser::class,
	    Commands\MailListRegisteredUser::class,
		Commands\MailForgotPassword::class,
	    Commands\MailRequestPassword::class,
		Commands\MailQueue::class,
	    Commands\MailListQueue::class,
	    Commands\MailListSalesOrder::class,
		Commands\MailConfirmPayment::class,
	    Commands\MailConfirmedPayment::class,
	    Commands\MailRejectedPayment::class,
	    Commands\MailListSalesInvoice::class,
	    Commands\MailReminderBooking::class,
	    Commands\MailListReminderBooking::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        $schedule->command('mail_registered_user')->everyMinute();
	    $schedule->command('mail_list_registered_user')->dailyAt('23:00');
		$schedule->command('mail_forgot_password')->everyMinute();
	    $schedule->command('mail_request_password')->everyMinute();
		$schedule->command('mail_queue')->everyMinute();
	    $schedule->command('mail_list_queue')->dailyAt('22:00');
	    $schedule->command('mail_list_sales_order')->dailyAt('23:20');
		$schedule->command('mail_confirm_payment')->everyMinute();
	    $schedule->command('mail_confirmed_payment')->everyMinute();
	    $schedule->command('mail_rejected_payment')->everyMinute();
	    $schedule->command('mail_list_sales_invoice')->dailyAt('23:30');
	    $schedule->command('mail_reminder_booking')->dailyAt('22:00');
	    $schedule->command('mail_list_reminder_booking')->dailyAt('23:40');
    }
}
