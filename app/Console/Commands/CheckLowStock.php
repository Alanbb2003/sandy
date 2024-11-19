<?php

namespace App\Console\Commands;

use App\Mail\LowStockNotification;
use App\Models\Products;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckLowStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:low-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check product stock levels and notify admin if below threshold';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $threshold = 50; 
        $lowStockProducts = Products::where('totalQuantity', '<', $threshold)->get();

        if ($lowStockProducts->isNotEmpty()) {
            $adminEmails = \App\Models\User::where('role', 1)->pluck('email');

            if ($adminEmails->isNotEmpty()) {
                Mail::to($adminEmails)->send(new LowStockNotification($lowStockProducts));
                $this->info('Low stock notification email sent to all admins.');
            } else {
                $this->warn('No admin users found to notify.');
            }
        } else {
            $this->info('No low stock items found.');
        }

        return 0;
    }
}
