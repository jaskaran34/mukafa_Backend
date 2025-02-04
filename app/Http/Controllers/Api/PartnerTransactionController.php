<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Card\TransactionService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Services\Staff\StaffService;
use App\Services\Card\CardService;
use App\Models\Member;
use App\Models\Card;
use App\Models\Settlement;

use Carbon\Carbon;
use App\Models\Transaction;
use Twilio\Rest\Client;

class PartnerTransactionController extends Controller
{

    public function send_sms()
    {
        $receiverNumber = '+917889481714'; 
        $message = 'Hello, this is a test SMS from my Laravel app using Twilio!';

        try {
            $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
            $twilio->messages->create($receiverNumber, [
                'from' => env('TWILIO_PHONE_NUMBER'),
                'body' => $message
            ]);

            return response()->json(['status' => 'Message sent successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'Error: ' . $e->getMessage()]);
        }
    }
    /**
     * Add a purchase transaction to a card where the authenticated partner has access to.
     *
     * @OA\Post(
     *     path="/{locale}/v1/partner/cards/{cardUID}/{memberUID}/transactions/purchases",
     *     operationId="addPurchase",
     *     tags={"Partner"},
     *     summary="Add a purchase to a card",
     *     description="Add a new purchase transaction to a card where the authenticated partner has access to.",
     *     security={{"partner_auth_token": {}}},
     *     
     *     @OA\Parameter(
     *         name="locale",
     *         in="path",
     *         description="Locale setting (e.g., `en-us`)",
     *         required=true,
     *         @OA\Schema(type="string", default="en-us")
     *     ),
     *     
     *     @OA\Parameter(
     *         name="cardUID",
     *         in="path",
     *         description="Unique identifier of the card to which the transaction will be added",
     *         required=true,
     *         @OA\Schema(type="string", format="xxx-xxx-xxx-xxx", example="123-456-789-012")
     *     ),
     *     
     *     @OA\Parameter(
     *         name="memberUID",
     *         in="path",
     *         description="Unique identifier for the member",
     *         required=true,
     *         @OA\Schema(type="string", format="xxx-xxx-xxx-xxx", example="123-456-789-012")
     *     ),
     *     
     *     @OA\RequestBody(
     *         description="Purchase data",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="purchase_amount",
     *                 type="number",
     *                 description="The amount of money spent on the purchase",
     *                 example=100
     *             ),
     *             @OA\Property(
     *                 property="image",
     *                 type="string",
     *                 description="The image associated with the transaction",
     *                 example=""
     *             ),
     *             @OA\Property(
     *                 property="note",
     *                 type="string",
     *                 nullable=true,
     *                 description="An optional note for the purchase",
     *                 example=""
     *             ),
     *             @OA\Property(
     *                 property="staffId",
     *                 type="string",
     *                 description="Staff ID",
     *                 format="Kra8\Snowflake\HasSnowflakePrimary",
     *                 example="50510833641460081"
     *             )
     *         )
     *     ),
     *     
     *     @OA\Response(
     *         response=201,
     *         description="Purchase created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Transaction")
     *     ),
     *     
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized access",
     *         @OA\JsonContent(ref="#/components/schemas/UnauthenticatedResponse")
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="Card not found",
     *         @OA\JsonContent(ref="#/components/schemas/NotFoundResponse")
     *     ),
     *     
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     )
     * )
     *
     * @param string $locale Locale setting (e.g., 'en-us')
     * @param string $cardUID Unique identifier of the card
     * @param string $memberUID Unique identifier for the member
     * @param Request $request The incoming HTTP request
     * @param TransactionService $transactionService Service to handle transaction-related operations
     * @return Response JSON response containing transaction details or error message
     */


     public function get_last_card($partner_id,$member_id){
        
        $card_last_transaction =Transaction::where('created_by', $partner_id)
         ->where('member_id', $member_id)
         ->where('status', 'completed')
         ->where('currency', 'QAR')
         ->whereNull('deleted_at')
         ->whereBetween('created_at', [now()->subYear(), now()])
         ->orderBy('created_at', 'desc') // Fetch the latest transaction
         ->select('card_id')
         ->first();

         return $card_last_transaction;
 
      }

     public function get_balance($partner_id,$member){

        $card_last_transaction = $this->get_last_card($partner_id,$member->id);


                    $card = Card::findOrFail($card_last_transaction['card_id']);
                

                    if($card_last_transaction['card_id']=='248378951208960'){
                        $points = $card->getMemberBalance($member);

                    }
                    elseif($card_last_transaction['card_id']=='248384746274816'){
                        
                        $points = $card->getMemberBalance($member);
    
                        $prev_card1='248378951208960';
                        $prev_card_find = Card::findOrFail($prev_card1);
                        $prev_balance=$prev_card_find->getMemberBalance($member);
                        $points=$points+$prev_balance;
    
                    }
                    elseif($card_last_transaction['card_id']=='248616202493952'){
                        $points = $card->getMemberBalance($member);
    
                        $prev_card1='248378951208960';
                        $prev_card_find = Card::findOrFail($prev_card1);
                        $prev_balance=$prev_card_find->getMemberBalance($member);
    
                        $prev_card2='248384746274816';
                        $prev_card_find2 = Card::findOrFail($prev_card2);
                        $prev_balance2=$prev_card_find2->getMemberBalance($member);
    
                        $points=$points+$prev_balance+$prev_balance2;
    
    
                    }
                    elseif($card_last_transaction['card_id']=='256023038738432'){
                        $points = $card->getMemberBalance($member);
    
                        $prev_card1='248378951208960';
                        $prev_card_find = Card::findOrFail($prev_card1);
                        $prev_balance=$prev_card_find->getMemberBalance($member);
    
                        $prev_card2='248384746274816';
                        $prev_card_find2 = Card::findOrFail($prev_card2);
                        $prev_balance2=$prev_card_find2->getMemberBalance($member);
    
                        $prev_card3='248616202493952';
                        $prev_card_find3 = Card::findOrFail($prev_card3);
                        $prev_balance3=$prev_card_find3->getMemberBalance($member);
    
                        $points=$points+$prev_balance+$prev_balance2+$prev_balance3;
    
                        
                    }

                    return number_format($points, 2, '.', '');

     }
     public function allmembers(Request $request)
     {
         $partner = $request->user('partner_api');
         $cardIds = $partner->cards->pluck('id');
     
         $per_page = $request->per_page ?? 10;
     
         if ($cardIds->isEmpty()) {
             return response()->json([], 200);
         }
     
         // Step 1: Fetch latest transaction per member and paginate directly
         $recentTransactions = Transaction::whereIn('card_id', $cardIds)
             ->select('member_id', 'card_id')
             ->orderBy('created_at', 'desc')
             ->distinct('member_id') // This ensures each member appears only once
             ->paginate($per_page);
     
         // Extract member IDs
         $memberIds = $recentTransactions->pluck('member_id')->toArray();
     
         // Map member_id => card_id
         $memberCardMap = $recentTransactions->pluck('card_id', 'member_id');
     
         // Step 2: Fetch only paginated members
         $members = Member::whereIn('id', $memberIds)
             ->orderBy('created_at', 'desc')
             ->get(); // No pagination here, as IDs are already paginated
     
         // Processing members
         $members->each(function ($member) use ($memberCardMap, $partner) {
             $member->createddate = Carbon::parse($member->created_at)->format('d-m-Y');
             $member->card_uid = Card::find($memberCardMap[$member->id])->unique_identifier ?? null;
             $member->card_name = Card::find($memberCardMap[$member->id])->name ?? null;
             $member->hideForPublic();
             $member->partner_id = $partner->id;
     
             if ($member->partner_id == '248216521760768') {
                 $member->balance = $this->get_balance($member->partner_id, $member);
             } else {
                 $member->balance = Card::find($memberCardMap[$member->id])->getMemberBalance($member) ?? null;
             }
     
             $member->balance_pending = Transaction::withTrashed()
                 ->where('status', 'pending')
                 ->where('card_id', $memberCardMap[$member->id])
                 ->where('member_id', $member->id)
                 ->whereNotNull('deleted_at')
                 ->sum('points');
         });
     
         // Format response data
         $data_member = $members->map(function ($member) {
             return [
                 "mukafa_number" => $member->unique_identifier,
                 "name" => $member->name,
                 "email" => $member->email,
                 "phone_prefix" => $member->phone_prefix,
                 "phone" => $member->phone,
                 "birthday" => $member->birthday,
                 "date_of_registeration" => $member->created_at,
                 "last_updated" => $member->updated_at,
                 "anniversary_date" => $member->anniversary_date,
                 "createddate" => $member->createddate,
                 "card_uid" => $member->card_uid,
                 "card_name" => $member->card_name,
                 "partner_id" => $member->partner_id,
                 "balance" => $member->balance,
                 "balance_pending" => $member->balance_pending
             ];
         });
     
         // Response with pagination
         return response()->json([
             'data' => $data_member,
             'pagination' => [
                 'total' => $recentTransactions->total(),
                 'per_page' => $recentTransactions->perPage(),
                 'current_page' => $recentTransactions->currentPage(),
                 'last_page' => $recentTransactions->lastPage(),
                 'next_page_url' => $recentTransactions->nextPageUrl(),
                 'prev_page_url' => $recentTransactions->previousPageUrl()
             ]
         ]);
     }
     
     
    public function addPurchase(
        string $locale,
        string $cardUID,
        string $memberUID,
        Request $request,
        TransactionService $transactionService,
        StaffService $staffService
    ): Response {

        //return response()->json(['error' => 'Dummy error'], 404);

        // Authenticate the partner using 'partner_api' guard
        $partner = $request->user('partner_api');

        // Validate the purchase data
        try {
            $validatedData = $this->validatePurchaseData($request);
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        }

        
        // Extract the image from the request
        $image = $request->file('image');

        $staff = $staffService->findActiveById($validatedData['staffId']);

        if (!$staff) {
            // Return an error response if the staff is not found
            return response()->json(['error' => 'Staff member not found'], 404);
        }

        

        
        
        // Create the new purchase
        $transaction = $transactionService->addPurchase(
            $memberUID,
            $partner->id,
            $cardUID,
            $staff,
            $validatedData['purchase_amount'],
            null,
            $image,
            $validatedData['note'],
            false
        );

        //return response()->json($transaction); 

        $tran=Transaction::findOrFail($transaction->id);        
        
        $tran->delete();


        $data = Transaction::withTrashed()
            ->where('id', $transaction->id)
            ->select('id', 'created_at', 'note','status')
            ->selectRaw('ABS(points) as points')
            ->selectRaw('IF(remarks = "Settlement", "Settlement", IF(points > 0, "Credit", "Debit")) as type')
            ->selectRaw('
                CASE 
                    WHEN deleted_at IS NULL AND status = "completed" THEN "completed"
                    WHEN deleted_at IS NOT NULL AND status = "cancelled" THEN "cancelled"
                    WHEN deleted_at IS NOT NULL AND status = "refunded" THEN "refunded"
                    WHEN deleted_at IS NOT NULL AND status = "pending" THEN "pending"
                END as status
')
            ->selectRaw('"Transaction Status success" as transaction_status')
            ->firstOrFail();
        // Return the transaction details in a JSON response
        return response()->json($data);
    }

    /**
     * Add points to a card where the authenticated partner has access to.
     *
     * @OA\Post(
     *     path="/{locale}/v1/partner/cards/{cardUID}/{memberUID}/transactions/points",
     *     operationId="addPoints",
     *     tags={"Partner"},
     *     summary="Add points to a card",
     *     description="Add new points to a card where the authenticated partner has access to.",
     *     security={{"partner_auth_token": {}}},
     *     
     *     @OA\Parameter(
     *         name="locale",
     *         in="path",
     *         description="Locale setting (e.g., `en-us`)",
     *         required=true,
     *         @OA\Schema(type="string", default="en-us")
     *     ),
     *     
     *     @OA\Parameter(
     *         name="cardUID",
     *         in="path",
     *         description="Unique identifier of the card to which the transaction will be added",
     *         required=true,
     *         @OA\Schema(type="string", format="xxx-xxx-xxx-xxx", example="123-456-789-012")
     *     ),
     *     
     *     @OA\Parameter(
     *         name="memberUID",
     *         in="path",
     *         description="Unique identifier for the member",
     *         required=true,
     *         @OA\Schema(type="string", format="xxx-xxx-xxx-xxx", example="123-456-789-012")
     *     ),
     *     
     *     @OA\RequestBody(
     *         description="Points data",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="points",
     *                 type="number",
     *                 description="The number of points to add",
     *                 example=100
     *             ),
     *             @OA\Property(
     *                 property="image",
     *                 type="string",
     *                 description="The image associated with the transaction",
     *                 example=""
     *             ),
     *             @OA\Property(
     *                 property="note",
     *                 type="string",
     *                 nullable=true,
     *                 description="An optional note for the points addition",
     *                 example=""
     *             ),
     *             @OA\Property(
     *                 property="staffId",
     *                 type="string",
     *                 description="Staff ID",
     *                 format="Kra8\Snowflake\HasSnowflakePrimary",
     *                 example="50510833641460081"
     *             )
     *         )
     *     ),
     *     
     *     @OA\Response(
     *         response=201,
     *         description="Points added successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Transaction")
     *     ),
     *     
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized access",
     *         @OA\JsonContent(ref="#/components/schemas/UnauthenticatedResponse")
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="Card not found",
     *         @OA\JsonContent(ref="#/components/schemas/NotFoundResponse")
     *     ),
     *     
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     )
     * )
     *
     * @param string $locale Locale setting (e.g., 'en-us')
     * @param string $cardUID Unique identifier of the card
     * @param string $memberUID Unique identifier for the member
     * @param Request $request The incoming HTTP request
     * @param TransactionService $transactionService Service to handle transaction-related operations
     * @return Response JSON response containing transaction details or error message
     */

     public function alltransactions(string $locale, Request $request)
     {
         $perPage = $request->get('per_page', 10);
     
         $partner = $request->user('partner_api');
     
         $query = Transaction::withTrashed()
             ->where('created_by', $partner->id)
             ->orderBy('created_at', 'desc')
             ->selectRaw('
                 id, created_at, purchase_amount, note, member_id, card_id, staff_id, deleted_at, status, remarks,
                 ABS(points) as points,
                 DATE_FORMAT(created_at, "%d-%m-%Y") as created_date
             ')
             ->with([
                 'staff:id,name,email',
                 'card:id,name,unique_identifier',
                 'member:id,unique_identifier,email,phone,name'
             ]);
     
         // **Filters**
         if ($request->filled('id')) {
             $query->where('id', 'like', '%' . $request->get('id') . '%');
         }
         if ($request->filled('from_date')) {
             $query->whereDate('created_at', '>=', $request->get('from_date'));
         }
         if ($request->filled('to_date')) {
             $query->whereDate('created_at', '<=', $request->get('to_date'));
         }
         if ($request->filled('note')) {
             $query->where('note', 'like', '%' . $request->get('note') . '%');
         }
     
       
            switch ($request->get('status')) {
                case 'success':
                case 'cancelled':
                case 'pending':
                case 'refunded':
                    $query->where('status', $request->get('status'))
                        ->where(function ($q) {
                            $q->where('remarks', '!=', 'Settlement')
                              ->orWhereNull('remarks');
                        })
                        ->selectRaw('IF(points > 0, "Credit", "Debit") as type');
                    break;
        
                case 'Settlement':
                    $query->where('remarks', 'Settlement')
                        ->selectRaw('"Settlement" as type') // ✅ Always "Settlement" type for settlement transactions
                        ->selectRaw('
                            (SELECT IF(points > 0, "Debit", "Credit") 
                             FROM transactions AS main 
                             WHERE main.id = transactions.note 
                             LIMIT 1) AS main_transaction_type
                        ')
                        ->selectRaw('
                            (SELECT ABS(points) 
                             FROM transactions AS main 
                             WHERE main.id = transactions.note 
                             LIMIT 1) AS main_transaction_points
                        ')
                        ->selectRaw('
                            (SELECT note 
                             FROM transactions AS main 
                             WHERE main.id = transactions.note 
                             LIMIT 1) AS main_transaction_note
                        ');
                    break;
        
                default:
                        $query->where('remarks', '!=', 'Settlement')         
                        ->selectRaw('IF(points > 0, "Credit", "Debit") as type');
                    break;
            }
       

        
         $data = $query->paginate($perPage)->appends($request->query());

        // return response()->json($data);
     
         // **Transform and Return Data**
         return response()->json([
             'current_page' => $data->currentPage(),
             'data' => $data->map(function ($item) {
                 return [
                     'id' => $item->id,
                     'created_at' => $item->created_at,
                     'order_id' => $item->note,
                     'purchase_amount' => $item->purchase_amount ?? null,
                     'mukafa_points' => $item->points,
                     'member_name' => $item->member->name ?? null,
                     'mukafa_number' => $item->member->unique_identifier ?? null,
                     'card_name' => $item->card->name ?? null,
                     'cardUID' => $item->card->unique_identifier ?? null,
                     'status' => $item->status,
                     'remarks' => $item->remarks ?? null,
                     'created_date' => $item->created_date,
                     'type' => $item->type,
                 ];
             }),
             'first_page_url' => $data->url(1),
             'from' => $data->firstItem() ?? 0,
             'last_page' => $data->lastPage(),
             'last_page_url' => $data->url($data->lastPage()),
             'next_page_url' => $data->nextPageUrl(),
             'prev_page_url' => $data->previousPageUrl(),
             'per_page' => $data->perPage(),
             'to' => $data->lastItem() ?? 0,
             'total' => $data->total() ?? 0
         ]);
     }
     

     public function cancel_transaction(string $locale,$tran_id, Request $request,TransactionService $transactionService,
     StaffService $staffService){

        $remarks= $request->input('remarks');

        
        $partner = $request->user('partner_api');

        $transaction = Transaction::withTrashed()->findOrFail($tran_id);
    
       

        $member=Member::findOrFail($transaction->member_id);

        
        
        $cancelled_transaction=$this->refundPoints($transaction->id,$transaction->card_id,$member->unique_identifier,$transaction->points,$transaction->staff_id,$transaction->note,$transactionService,$staffService);
        

        
        
        $card=$transaction->card;
        $member_mod=$transaction->member;

        $balance=$card->getMemberBalance($member_mod);


        $transaction->status='cancelled';
        $transaction->remarks=$remarks;
        $transaction->save();

        return response()->json(['id' => $cancelled_transaction->remarks,'points'=>abs($cancelled_transaction->points),'balance'=>$balance,
        'deleted_at'=>$transaction->deleted_at,
        'cancel_tran_id'=> $cancelled_transaction->id,
        'remarks'=>$transaction->remarks
    ], 200);
     }
     public function findmember(string $locale,string $memberUID, Request $request){

        $partner = $request->user('partner_api');
        $partner_id=$partner->id;

        //return $partner;
        $member =Member::where('unique_identifier',$memberUID)->first();
        if(!$member) {
            return response()->json(['message' => 'Member not found'], 404);
        }
        else{

            if($partner_id=='248216521760768'){
                $points=$this->get_balance($partner_id,$member); 
                $card_last_transaction = $this->get_last_card($partner_id,$member->id);
                $card = Card::findOrFail($card_last_transaction['card_id']);

            }
            else{
                $card_last_transaction = $this->get_last_card($partner_id,$member->id);
                $card = Card::findOrFail($card_last_transaction['card_id']);
                $points=$card->getMemberBalance($member);
            }

            

            

            
        

             $pending_points = Transaction::onlyTrashed()
             ->where('member_id', $member->id)
             ->where('created_by', $partner->id)
             ->where('status','pending')
             ->sum('points');

            
             


             if($partner->currency=='QAR'){
                $amount=((int)$points)/100;
             }
            
             $data_member=[
        "mukafa_number"=> $member->unique_identifier,
        "name"=> $member->name,
        "email"=> $member->email,
        "birthday"=> $member->birthday,
        "phone_prefix"=> $member->phone_prefix,
        "phone"=> $member->phone,
        "date_of_registeration"=> $member->created_at,
        "last_updated"=> $member->updated_at,
        "anniversary_date" =>$member->anniversary_date,
        
             ];

            return response()->json([
                'member' => $data_member,
                'balance'=>$points,
                'amount'=>$amount,
                'currency'=>$partner->currency,
                'cardUID'=>$card->unique_identifier,
                'card_name'=>$card->name,
                'pending_points'=>$pending_points
            ], 200);
        }
        
     }
     public function redeemPoints(string $locale,
     string $cardUID,
     string $memberUID,
     Request $request,
     TransactionService $transactionService,
     CardService $CardService,
          StaffService $staffService): Response
     {
        $partner = $request->user('partner_api');
        try {
            $validatedData = $this->validateredeemData($request);
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        
        }
        

// Extract the image from the request
$image = $request->file('image');

$staff = $staffService->findActiveById($validatedData['staffId']);



if (!$staff) {
    // Return an error response if the staff is not found
    return response()->json(['error' => 'Staff member not found'], 404);
}

$card=$CardService->findActiveCardByIdentifier($cardUID);

$transaction = $transactionService->redeemReward(
    $card->id, 
    $partner->id,
    $validatedData['points'], 
    $memberUID, 
    $staff,  
    $request->image, 
    $request->note
);

$data = Transaction::withTrashed()
            ->where('id', $transaction->id)
            ->select('id', 'created_at', 'note','status')
            ->selectRaw('ABS(points) as points')
            ->selectRaw('IF(remarks = "Settlement", "Settlement", IF(points > 0, "Credit", "Debit")) as type')
            ->selectRaw('
                CASE 
                    WHEN deleted_at IS NULL AND status = "completed" THEN "completed"
                    WHEN deleted_at IS NOT NULL AND status = "cancelled" THEN "cancelled"
                    WHEN deleted_at IS NOT NULL AND status = "refunded" THEN "refunded"
                    WHEN deleted_at IS NOT NULL AND status = "pending" THEN "pending"
                END as status
')
            ->selectRaw('"Transaction Status success" as transaction_status')
            ->firstOrFail();




// Return the transaction details in a JSON response
return response()->json($data);

     }



     public function refundPoints($reference,$cardid,$memberUID,$points,$staffid,$note,
     TransactionService $transactionService,
          StaffService $staffService)
     {
        
        
        
$staff = $staffService->findActiveById($staffid);


$transaction = $transactionService->refund(
    $reference,
    $cardid, 
    $points, 
    $memberUID, 
    $staff,  
    NULL, 
    $note
);





// Return the transaction details in a JSON response
return $transaction;

     }
     
    public function addPoints(
        string $locale,
        string $cardUID,
        string $memberUID,
        Request $request,
        TransactionService $transactionService,
        StaffService $staffService
    ): Response {
        // Authenticate the partner using 'partner_api' guard
        $partner = $request->user('partner_api');

        // Validate the points data
        try {
            $validatedData = $this->validatePointsData($request);
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        }

        // Extract the image from the request
        $image = $request->file('image');

        $staff = $staffService->findActiveById($validatedData['staffId']);

        if (!$staff) {
            // Return an error response if the staff is not found
            return response()->json(['error' => 'Staff member not found'], 404);
        }

        // Add the new points
        $transaction = $transactionService->addPurchase(
            $memberUID,
            $cardUID,
            $staff,
            null,
            $validatedData['points'],
            $image,
            $validatedData['note'],
            true
        );

        
               

        // Return the transaction details in a JSON response
        return response()->json($transaction);
    }
 
    /**
     * Validate the purchase data.
     *
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    private function validatePurchaseData(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'purchase_amount' => 'required|numeric|min:0',
            'note' => 'nullable|max:1024',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'staffId' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
 
    /**
     * Validate the points data.
     *
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    
     private function validateredeemData(Request $request): array
     {
         $validator = Validator::make($request->all(), [
             'points' => 'required|numeric|min:1',
             'note' => 'nullable|max:1024',
             'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
             'staffId' => 'required|numeric',
         ]);
 
         if ($validator->fails()) {
             throw new ValidationException($validator);
         }
 
         return $validator->validated();
     }

     private function validatePointsData(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'points' => 'required|numeric|min:0',
            'note' => 'nullable|max:1024',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'staffId' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
 
     /**
      * Handle the validation exception and return a proper JSON response.
      *
      * @param ValidationException $exception
      * @return Response
      */
     private function handleValidationException(ValidationException $exception): Response
     {
         $errors = $exception->errors();
 
         return response()->json([
             'message' => 'The given data was invalid.',
             'errors' => $errors,
         ], 422);
     }
     public function gettransactions(string $locale,string $memberUID, Request $request){

        $partner = $request->user('partner_api');
        
        $member=Member::where('unique_identifier',$memberUID)->first();

        if(!$member) {
            return response()->json(['message' => 'Member not found'], 404);
        }
        
            
        
        $data = Transaction::withTrashed()
            ->where('member_id', $member->id)
            ->where('created_by', $partner->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->select('id', 'created_at', 'purchase_amount', 'note', 'staff_id', 'card_id','event','status','remarks')
            ->selectRaw('ABS(points) as points')
            ->selectRaw('DATE_FORMAT(created_at, "%d-%m-%Y") as created_date')            
            ->where(function ($query) {
                $query->where('remarks', '!=', 'Settlement')
                      ->orWhereNull('remarks');
            })
            ->selectRaw('IF(points > 0, "Credit", "Debit") as type')
            //->selectRaw('IF(points > 0, "Credit", "Debit") as type')
            //->selectRaw('IF(remarks = "Settlement", "Settlement", IF(points > 0, "Credit", "Debit")) as type')
            ->selectRaw('
                CASE 
                    WHEN deleted_at IS NULL AND status = "completed" THEN "completed"
                    WHEN deleted_at IS NOT NULL AND status = "cancelled" THEN "cancelled"
                    WHEN deleted_at IS NOT NULL AND status = "refunded" THEN "refunded"
                    WHEN deleted_at IS NOT NULL AND status = "pending" THEN "pending"
                END as status
')
            ->with([
                'staff' => function ($query) {
                    $query->select('id', 'name', 'email','unique_identifier');
                },
                'card' => function ($query) {
                    $query->select('id', 'name','unique_identifier');
                },
            ])
            ->get();



            
            $data_send=[];


            foreach($data as $dt){
                $arr_send=[
                    'id'=>$dt->id,
                    'created_at'=>$dt->created_at,
                    'order_id'=>$dt->note,
                    'mukafa_number'=>$memberUID,
                    'purchase_amount'=>$dt->purchase_amount,
                    'mukafa_points'=>$dt->points,
                    'card_name'=>$dt->card->name,
                    'cardUID'=>$dt->card->unique_identifier,
                    'status'=>$dt->status,
                    'remarks'=>$dt->remarks,
                    'created_date'=>$dt->created_date,
                    'type'=>$dt->type,
                    'partner'=>$partner->id,
                ];
                array_push($data_send,$arr_send);
            }
    

        return response()->json(['data' => $data_send], 200);
     }
}
