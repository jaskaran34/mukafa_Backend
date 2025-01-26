<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Partner;
use App\Models\Transaction;
use App\Models\Card;
use App\Models\TransactionRefundSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
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
        $currentTime = Carbon::now()->toDateTimeString();
        $logMessage = "\nScheduler ran at: {$currentTime}\n";
        File::append(public_path('scheduler_log.txt'), $logMessage);

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

                    if($partner->id=='248216521760768'){
                        //amount_member_spent_in_last_one_year
                        $amount=Transaction::where('created_by', $partner->id)
                        ->where('member_id', $transaction->member_id)
                        ->where('status', 'completed')
                        ->where('currency', 'QAR')
                        ->whereNull('deleted_at')
                        ->whereBetween('created_at', [now()->subYear(), now()]) 
                        ->sum('purchase_amount');

                        $card_last_transaction = Transaction::where('created_by', $partner->id)
                        ->where('member_id', $transaction->member_id)
                        ->where('status', 'completed')
                        ->where('currency', 'QAR')
                        ->whereNull('deleted_at')
                        ->whereBetween('created_at', [now()->subYear(), now()])
                        ->orderBy('created_at', 'desc') // Fetch the latest transaction
                        ->select('card_id')
                        ->first(); // Retrieve the first result

                        $newCardId = null; // Placeholder for the new card ID

                        if ((int)$amount > 10000 && (int)$amount < 50000) {
                            $newCardId = '248384746274816';
                        } elseif ((int)$amount > 50000 && (int)$amount < 150000) {
                            $newCardId = '248616202493952';
                        } elseif ((int)$amount > 150000) {
                            $newCardId = '256023038738432';
                        } else {
                            $newCardId = '248378951208960';
                        }

                        $card = Card::findOrFail($newCardId);

                         

                        // Check if the card has changed, and send an SMS if needed
                        if ($card_last_transaction !== $newCardId) {

                            $staff=$partner->superadminstaff->first();
            
                
                            $created_at =  Carbon::now();
                            $expires_at = (!$created_at instanceof Carbon) ? Carbon::parse($created_at) : $created_at->copy();
                
                            $data = [
                                'staff_id' => $staff->id,
                                'member_id' => $transaction->member_id,
                                'card_id' => $card->id,
                                'partner_name' => $partner->name,
                                'partner_email' => $partner->email,
                                'staff_name' => $staff->name,
                                'staff_email' => $staff->email,
                                'card_title' => $card->getTranslations('head'),
                                'currency' => $card->currency,
                                'points_per_currency' => $card->points_per_currency,
                                'meta' => [
                                    'round_points_up' => $card->meta && is_array($card->meta) && isset($card->meta['round_points_up']) ? (bool) $card->meta['round_points_up'] : true
                                ],
                                'min_points_per_purchase' => $card->min_points_per_purchase,
                                'max_points_per_purchase' => $card->max_points_per_purchase,
                                'expires_at' => $expires_at->addMonths($card->points_expiration_months)->format('Y-m-d H:i:s'),
                                'created_by' => $partner->id,
                            ];
                            $data['purchase_amount'] = null;
                            $data['note'] = 'Issue Initial Bonus '.$card->name;
                            
                
                            if ($card->initial_bonus_points && !Transaction::where('member_id', $transaction->member_id)->where('card_id', $card->id)->exists()) {
                                $bonusData = array_merge($data, [
                                    'points' => $card->initial_bonus_points,
                                    'event' => 'initial_bonus_points',
                                    'status' => 'completed',
                                    'created_at' => $created_at,
                                    'updated_at' => $created_at,
                                ]);
                                $transaction = Transaction::create($bonusData);
                    
                                
                            }

                        }
                        
                    }


               
                    $t_id = "Transaction {$transaction->id}\n";
                    File::append(public_path('scheduler_log.txt'), $t_id);
                    // Output the success message
                    $this->info("Transaction {$transaction->id} restored and status updated to completed.");
                }
            }

        }



        
    }
}
