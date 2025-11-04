<?php

namespace App\Console\Commands;

use App\Mail\BirthdayWishMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckBirthdays extends Command
{
    protected $signature = 'check:birthdays';
    protected $description = 'Send birthday emails to users in membership who have birthdays today';

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $today = Carbon::today()->format('m-d');
        //untuk coba
        //php artisan check:birthdays
        User::whereHas('membership', function ($query) {
            $query->where('statusMembership', 1); 
        })
        ->whereNotNull('tanggalLahir') 
        ->whereRaw('DATE_FORMAT(tanggalLahir, "%m-%d") = ?', [$today]) 
        ->chunk(100, function ($users) { 
            foreach ($users as $user) {

                Mail::to($user->email)->send(new BirthdayWishMail($user));

                $this->info('Birthday email sent to: ' . $user->email);
            }
        });

        return 0;
    }
}
