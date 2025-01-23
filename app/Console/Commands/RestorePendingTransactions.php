<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Partner;
use App\Models\Transaction;
use App\Models\TransactionRefundSetting;
use Carbon\Carbon;

class RestorePendingTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restore-pending-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'restore-pending-transactions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        

       // $partners=Partner::all();

       $partners = Partner::whereHas('transactions', function ($query) {
        $query->where('status', 'pending')->withTrashed(); // Optional: Filter based on transaction status if needed
    })->get();

        foreach($partners as $partner){

            
            $TransactionRefundSetting=TransactionRefundSetting::where('partner',$partner->id)->first();

            if (!$TransactionRefundSetting) {
                $this->error("No return time setting found for partner {$partner->email}");
                continue;
            }

            $transactions = Transaction::onlyTrashed()
            ->where('status', 'pending')
            ->where('created_by', $partner->id)
            ->get();

            foreach ($transactions as $transaction) {
        
                
                $now = Carbon::now($partner->time_zone);  // Get the current time in Asia/Qatar timezone
                
                $transactionCreatedAt = Carbon::parse($transaction->created_at);  // Ensure the transaction time is in Asia/Qatar timezone
                
                
                // Calculate the time difference
                $timeDifference = (int)floor($transactionCreatedAt->diffInSeconds($now));

               
                // If the time difference is greater than 3600 seconds (1 hour)
                if ($timeDifference > (int) $TransactionRefundSetting->return_time) {
                    // Restore the transaction and update the status to completed
                    $transaction->restore();
                    $transaction->status = 'completed';
                    $transaction->save();

                    ////

                    /*
                    if($partner->id=='248216521760768'){
                        //amount_member_spent_in_last_one_year
                        $amount=Transaction::where('created_by', $partner->id)
                        ->where('member_id', $transaction->member_id)
                        ->where('status', 'completed')
                        ->where('currency', 'QAR')
                        ->whereNull('deleted_at')
                        ->whereBetween('created_at', [now()->subYear(), now()]) 
                        ->sum('purchase_amount');
            
                    
            
                 if((int)$amount> 10000 and (int)$amount<50000) {
                         $card=Card::findOrFail('248384746274816');
                 }   
                 else if((int)$amount> 50000 and (int)$amount<150000){
                     $card=Card::findOrFail('248616202493952');
                 }
                 else{
                     $card=Card::findOrFail('248378951208960');
                 }



*/



                    ////
    
                    // Output the success message
                    $this->info("Transaction {$transaction->id} restored and status updated to completed.");
                }
            }

        }



        
    }
}
