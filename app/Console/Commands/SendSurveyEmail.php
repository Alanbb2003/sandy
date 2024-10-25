<?php

namespace App\Console\Commands;

use App\Mail\SurveyEmail;
use App\Models\Htrans;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendSurveyEmail extends Command
{
    protected $signature = 'email:send-survey';
    protected $description = 'Send survey emails for transactions completed a week ago';
    
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Get transactions completed exactly 7 days ago
        $oneWeekAgo = Carbon::now()->subWeek();
        
        $transactions = Htrans::where('status', 3) // Completed
            ->whereDate('updated_at', $oneWeekAgo->toDateString())
            ->with('user')
            ->get();

        foreach ($transactions as $transaction) {
            // Send survey email
            Mail::to($transaction->user->email)->send(new SurveyEmail($transaction));

            $this->info('Survey email sent to: ' . $transaction->user->email);
        }

        return 0;
    }
}
