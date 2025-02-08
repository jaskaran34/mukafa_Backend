<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Card\TransactionService;
use App\Services\Member\MemberService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Services\Staff\StaffService;
use App\Services\Card\CardService;
use App\Models\Member;
use App\Models\Partner;
use Illuminate\Support\Arr;
use App\Models\Card;
use App\Models\Staff;
use App\Models\Settlement;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;
use App\Models\Transaction;
use Twilio\Rest\Client;

class PartnerTransactionController extends Controller
{
    public function sendCustomEmail($subject, $email_to,$html)
    {
        $curl = curl_init();
        
        $payload = json_encode([
            "from" => [
                "email" => "hello@js.qa",
                "name" => "Mukafa"
            ],
            "to" => [
                [
                    "email" => $email_to
                ]
            ],
            "subject" => $subject,
            "html" => $html
        ]);
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://send.api.mailtrap.io/api/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer 9fd3a6f8b96af010062a5a80d3ddfd3f',
                'Content-Type: application/json'
            ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        
    }

    public function register(Request $request, MemberService $memberService)
    {
        //return response()->json($request);

        if($request->partner_id && $request->partner_id=='248216521760768'){
        
        // Validate request inputs
        $request->validate([
            'email' => 'nullable|email|max:96|unique:members',
            'phone' => ['required', 'regex:/^[0-9]{8,10}$/', 'unique:members'],
            'phone_prefix'=>'required|min:2|max:4',
            'name' => 'required|max:64',
            'password' => 'nullable|min:6|max:48',
            'time_zone' => 'nullable',
            'accepts_emails' => 'nullable|boolean',
            'send_mail' => 'nullable|boolean',
            'locale' => 'nullable|min:5|max:12',
            'currency' => 'nullable|min:3|max:3',
            'birthday' => 'required|date',
            'anniversary_date' => 'nullable|date',
            
        ]);
    
     

        $locale = $request->input('locale', 'en_US'); 
        $currency = $request->input('currency','QAR');
        $time_zone = $request->input('time_zone', 'Asia/Qatar');
        $send_mail = $request->input('send_mail', 0);

        /*
        // Get or set default values for optional parameters
        $i18n = app()->make('i18n');
        $locale = $request->input('locale', $i18n->language->current->locale);
        $currency = $request->input('currency', $i18n->currency->id);
        $time_zone = $request->input('time_zone', $i18n->time_zone);
        $send_mail = $request->input('send_mail', 0);
    */
        // Generate password if not provided
        $password = $request->input('password');
        if (is_null($password)) {
            $password = implode('', Arr::random(range(0, 9), 6));
        }


    
        // Prepare response array
        $response = [
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'birthday' => $request->input('birthday'),
            'anniversary_date' => $request->input('anniversary_date'),
            'phone_prefix' => $request->input('phone_prefix'),
            'password' => $password,
            'time_zone' => $time_zone,
            'accepts_emails' => (int) $request->input('accepts_emails', 0),
            'send_mail' => (int) $send_mail,
            'locale' => $locale,
            'currency' => $currency,
        ];
    
        // Prepare member array for storing in the database
        $member = $response;
       
        $member['password'] = bcrypt($password);
    
        // 'send_mail' should not be stored in the database
        $member = Arr::except($member, ['send_mail']);
    
        // Save new member to database
        $newMember = $memberService->store($member);

        $response=[
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'birthday' => $request->input('birthday'),
        ];
    
        
          
            $partner=Partner::findOrFail($request->partner_id);
            $staff=$partner->superadminstaff->first();
            
            //$cards=$partner->cards;
            $card=Card::where('id','248378951208960')->where('created_by',$partner->id)->first();

            $created_at =  Carbon::now();
            $expires_at = (!$created_at instanceof Carbon) ? Carbon::parse($created_at) : $created_at->copy();

            $data = [
                'staff_id' => $staff->id,
                'member_id' => $newMember->id,
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
            $data['note'] = 'Issue Initial Bonus';
            

            if ($card->initial_bonus_points && !Transaction::where('member_id', $newMember->id)->where('card_id', $card->id)->exists()) {
                $bonusData = array_merge($data, [
                    'points' => $card->initial_bonus_points,
                    'event' => 'initial_bonus_points '.$card->name,
                    'status' => 'completed',
                    'created_at' => $created_at,
                    'updated_at' => $created_at,
                ]);
                $transaction = Transaction::create($bonusData);
    
                
            }

       



        // Send registration mail if requested
        if ((int) $send_mail === 1) {
            $newMember->notify(new Registration($member['email'], $password, 'member'));
        }
        $response['unique_identifier']=$newMember->unique_identifier; 


        $points=$this->get_balance($partner->id,$newMember); 
                $card_last_transaction = $this->get_last_card($partner->id,$newMember->id);
                $card = Card::findOrFail($card_last_transaction['card_id']);



                $pending_points = Transaction::onlyTrashed()
             ->where('member_id', $newMember->id)
             ->where('created_by', $partner->id)
             ->where('status','pending')
             ->sum('points');

            
             


             if($partner->currency=='QAR'){
                $amount=((int)$points)/100;
             }
            
             $data_member=[
        "mukafa_number"=> $newMember->unique_identifier,
        "name"=> $newMember->name,
        "email"=> $newMember->email,
        "birthday"=> $newMember->birthday,
        "phone_prefix"=> $newMember->phone_prefix,
        "phone"=> $newMember->phone,
        "date_of_registeration"=> $newMember->created_at,
        "last_updated"=> $newMember->updated_at,
        "anniversary_date" =>$newMember->anniversary_date,
        
             ];

    $html="<!DOCTYPE html><html lang=\"en\"><head><meta charset=\"UTF-8\"><meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"><style>body {font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;} .container {max-width: 600px; margin: 20px auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);} .header {background-color: #4CAF50; color: #ffffff; padding: 10px 0; text-align: center; border-radius: 8px 8px 0 0;} .content {padding: 20px;} .message {padding: 10px; text-align: center; font-size: 18px; font-weight: bold; border-radius: 4px;}</style></head><body><div class=\"container\"><div class=\"header\"><h1>Member Registered!</h1></div><div class=\"content\"><p>Dear User,</p><p>We're thrilled to have you join our community. Your registration was successful, and we're excited to embark on this journey with you. At Mukafa, we're dedicated to providing you with the best experience possible.</p><p>Thank you,</p><p>Mukafa Number: ".$newMember->unique_identifier."</p><p>500 mukafa points have been added to your mukafa account</p></div><div class=\"footer\">&copy; 2025 Mukafa. All rights reserved.</div></div></body></html>";

$this->sendCustomEmail('Customer Registered','jaskaran9056@gmail.com',$html);

            return response()->json([
                'member' => $data_member,
                'balance'=>$points,
                'amount'=>$amount,
                'currency'=>$partner->currency,
                'cardUID'=>$card->unique_identifier,
                'card_name'=>$card->name,
                'pending_points'=>$pending_points
            ], 200);






        // Return a response with member details
        return response()->json($response, 201);
    }
    else{
        return response()->json(['error'=>'Partner Id Missing or not found'], 404);
    } 
    }

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

        $staff = $staffService->findActiveById($validatedData['integration_id']);

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
            $validatedData['order_id'],
            false
        );

        //return response()->json($transaction); 

        $tran=Transaction::findOrFail($transaction->id);        
        
        $tran->delete();


        $data = Transaction::withTrashed()
        ->where('id', $transaction->id)
        ->select('id', 'created_at', 'note','purchase_amount', 'status', 'staff_id as integration_id','card_id','member_id')
        ->selectRaw('ABS(points) as points')
        ->selectRaw('DATE_FORMAT(created_at, "%d-%m-%Y") as created_date')
        ->selectRaw('IF(remarks = "Settlement", "Settlement", IF(points > 0, "Credit", "Debit")) as type')
        ->selectRaw('
            CASE 
                WHEN deleted_at IS NULL AND status = "completed" THEN "completed"
                WHEN deleted_at IS NOT NULL AND status = "cancelled" THEN "cancelled"
                WHEN deleted_at IS NOT NULL AND status = "refunded" THEN "refunded"
                WHEN deleted_at IS NOT NULL AND status = "pending" THEN "pending"
            END as status')
        ->with([
            'card:id,name,unique_identifier',
            'member:id,unique_identifier'
        ])
        ->firstOrFail();

        $data_send=[
            'id'=>$data->id,
            'created_at'=>$data->created_at,
            'mukafa_number'=>$data->member->unique_identifier,
            'card_name'=>$data->card->name,
            'carduid'=>$data->card->unique_identifier,
            'order_id'=>$data->note,
            'status'=>$data->status,
            'integration_id'=>$data->integration_id,
            'purchase_amount'=>$data->purchase_amount,
            'mukafa_points'=>$data->points,
            'created_date'=>$data->points,
            'type'=>$data->type,
        ];
    

        $html="<!DOCTYPE html><html lang=\"en\"><head><meta charset=\"UTF-8\"><meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"><style>body {font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;} .container {max-width: 600px; margin: 20px auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);} .header {background-color: #4CAF50; color: #ffffff; padding: 10px 0; text-align: center; border-radius: 8px 8px 0 0;} .content {padding: 20px;} .message {padding: 10px; text-align: center; font-size: 18px; font-weight: bold; border-radius: 4px;}</style></head><body><div class=\"container\"><div class=\"header\">
        <h1>Puchase Successful!</h1>
        </div><div class=\"content\">
        <p>Dear User,</p>
        <p>We are excited to let you know that your purchase was successful!</p>
        <p>Transaction Id ".$data->id."</p>
        <p>Mukafa Points earned ".$data->points."</p>
        <p>Thank you,</p>
        </div><div class=\"footer\">&copy; 2025 Mukafa. All rights reserved.</div></div></body></html>";

$this->sendCustomEmail('Purchase Added','jaskaran9056@gmail.com',$html);
        

        // Return the transaction details in a JSON response
        return response()->json($data_send,201);
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
         if ($request->filled('transaction_id')) {
             $query->where('id', 'like', '%' . $request->get('transaction_id') . '%');
         }
         if ($request->filled('from_date')) {
             $query->whereDate('created_at', '>=', $request->get('from_date'));
         }
         if ($request->filled('to_date')) {
             $query->whereDate('created_at', '<=', $request->get('to_date'));
         }
         if ($request->filled('order_id')) {
             $query->where('note', 'like', '%' . $request->get('order_id') . '%');
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

        $cancel_type= $request->input('cancel_type');
        
        $partner = $request->user('partner_api');
       
        try {
            $transaction = Transaction::withTrashed()->where('status', 'pending')->findOrFail($tran_id);
            
            if($transaction->cancel_flag=='C'){
                if($cancel_type=='F'){
                    return response()->json([
                        'Error'=>'Transaction Already Partially Cancelled.' 
                    ]);
                }
                else if($cancel_type=='P'){
                
                   
                        $sum_cancelled= Transaction::withTrashed()
                        ->where('status', 'completed')
                        ->where('remarks', $transaction->id)
                        ->where('cancel_flag', 'R')
                        ->sum('points');

                       

                        if($request->input('points')>($transaction->points - abs($sum_cancelled) )){
                            return response()->json([
                                'Error'=>'Cancelled Amount greater than transaction balance' 
                            ]);
                        }
    
                        else{
                            $cancel_points=$request->input('points');
                        }

                        if($request->input('points')==($transaction->points - abs($sum_cancelled) )){
                            $flag_update='Y';
                        }


                }
                
            }
            else{

            
           
        if($cancel_type=='F'){
            $cancel_points=$transaction->points;
            
        }

        else if($cancel_type=='P'){
            
            $cancel_points=$request->input('points');
            
            if($transaction->points < $cancel_points){
                return response()->json([
                    'Error'=>'Points to be cancelled more than original transaction' 
                ]);
            }
            else if($transaction->points > $cancel_points){
                   
            }
            else{
                return response()->json([
                    'Error'=>'Invalid Data' 
                ]);
            }
            
            
        }
        else{
            return response()->json([
                'Error'=>'Invalid cancel_type' 
            ]);
        }

    }
                $member=Member::findOrFail($transaction->member_id);

        
        
        $cancelled_transaction=$this->refundPoints($transaction->id,$transaction->card_id,$member->unique_identifier,$cancel_points,$transaction->staff_id,$transaction->note,$transactionService,$staffService);
        
        $card=$transaction->card;
        $member_mod=$transaction->member;

        if($cancel_type=='F'){
       $transaction->restore();
        $transaction->status='completed';
        $transaction->remarks=$remarks;
        
  }
  else if($cancel_type=='P'){
    if(isset($flag_update) && $flag_update=='Y'){
        $transaction->restore();
        $transaction->status='completed';

        
    }
    if (!empty($transaction->remarks)) {
        $transaction->remarks .= ', ' . $remarks; // Append with a comma
    } else {
        $transaction->remarks = $remarks;
    }
    
  }
        $transaction->cancel_flag='C';
     
        $transaction->save();

        if($partner->id=='248216521760768'){
            $balance=$this->get_balance($partner->id,$member_mod); 

            $pending_points = Transaction::onlyTrashed()
             ->where('member_id', $member_mod->id)
             ->where('created_by', $partner->id)
             ->where('status','pending')
             ->sum('points');
        }

        return response()->json([
            'cancel_tran_id'=> $cancelled_transaction->id,
            'refrence_tran_id' => $cancelled_transaction->remarks,
            'points_returned'=>abs($cancel_points),
            'balance'=>$balance,
            'cancel_time'=>$transaction->updated_at,
            'reason_for_cancel'=>$transaction->remarks,
            'pending_points'=>$pending_points
    ], 200);
} catch (ModelNotFoundException $e) {

    $checktransaction = Transaction::withTrashed()
        ->where('status', 'completed')
        ->where('cancel_flag', 'C')
        ->find($tran_id);

    if ($checktransaction) {
        return response()->json([
            'message' => 'Transaction Already Cancelled.'
        ], 404);
    } else {
        // If the transaction is not found
        return response()->json([
            'message' => 'Transaction not found.'
        ], 404);
    }

}

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

$staff = $staffService->findActiveById($validatedData['integration_id']);



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
    $request->order_id
);

            
        $data = Transaction::withTrashed()
        ->where('id', $transaction->id)
        ->select('id', 'created_at', 'note', 'status', 'staff_id as integration_id','card_id','member_id')
        ->selectRaw('ABS(points) as points')
        ->selectRaw('DATE_FORMAT(created_at, "%d-%m-%Y") as created_date')
        ->selectRaw('IF(remarks = "Settlement", "Settlement", IF(points > 0, "Credit", "Debit")) as type')
        ->selectRaw('
            CASE 
                WHEN deleted_at IS NULL AND status = "completed" THEN "completed"
                WHEN deleted_at IS NOT NULL AND status = "cancelled" THEN "cancelled"
                WHEN deleted_at IS NOT NULL AND status = "refunded" THEN "refunded"
                WHEN deleted_at IS NOT NULL AND status = "pending" THEN "pending"
            END as status')
        ->with([
            'card:id,name,unique_identifier',
            'member:id,unique_identifier'
        ])
        ->firstOrFail();

        $data_send=[
            'id'=>$data->id,
            'created_at'=>$data->created_at,
            'mukafa_number'=>$data->member->unique_identifier,
            'card_name'=>$data->card->name,
            'carduid'=>$data->card->unique_identifier,
            'order_id'=>$data->note,
            'status'=>$data->status,
            'integration_id'=>$data->integration_id,
            'mukafa_points'=>$data->points,
            'created_date'=>$data->points,
            'type'=>$data->type,
        ];

        $html="<!DOCTYPE html><html lang=\"en\"><head><meta charset=\"UTF-8\"><meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"><style>body {font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;} .container {max-width: 600px; margin: 20px auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);} .header {background-color: #4CAF50; color: #ffffff; padding: 10px 0; text-align: center; border-radius: 8px 8px 0 0;} .content {padding: 20px;} .message {padding: 10px; text-align: center; font-size: 18px; font-weight: bold; border-radius: 4px;}</style></head><body><div class=\"container\"><div class=\"header\">
        <h1>Redemption Successful!</h1>
        </div><div class=\"content\">
        <p>Dear User,</p>
        <p>We are excited to let you know that your redemption was successful!</p>
        <p>Transaction Id ".$data->id."</p>
        <p>Mukafa Points redeemed ".$data->points."</p>
        <p>Thank you,</p>
        </div><div class=\"footer\">&copy; 2025 Mukafa. All rights reserved.</div></div></body></html>";

        $this->sendCustomEmail('Redemption added','jaskaran9056@gmail.com',$html);
        
    
        

        // Return the transaction details in a JSON response
        return response()->json($data_send,201);



// Return the transaction details in a JSON response
//return response()->json($data);

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
            'order_id' => 'nullable|max:1024|unique:transactions,note',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'integration_id' => 'required|numeric',
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
             'order_id' => 'nullable|max:1024|unique:transactions,note',
             'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
             'integration_id' => 'required|numeric',
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

     public function send_otp(string $locale,$otp){

        $html="<!DOCTYPE html><html lang=\"en\"><head><meta charset=\"UTF-8\"><meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"><style>body {font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;} .container {max-width: 600px; margin: 20px auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);} .header {background-color: #4CAF50; color: #ffffff; padding: 10px 0; text-align: center; border-radius: 8px 8px 0 0;} .content {padding: 20px;} .message {padding: 10px; text-align: center; font-size: 18px; font-weight: bold; border-radius: 4px;}</style></head><body><div class=\"container\"><div class=\"header\">
        <h1>Otp Received</h1>
        </div><div class=\"content\">
        <p>Dear User,</p>
        <p>Otp is <span style=\"color:red;\"> ".$otp."</span></p>
        <p>Thank you,</p>
        </div><div class=\"footer\">&copy; 2025 Mukafa. All rights reserved.</div></div></body></html>";

$this->sendCustomEmail('Otp','jaskaran9056@gmail.com',$html);
        

        return response()->json(['message' => 'otp sent'], 200);

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
