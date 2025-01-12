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

use Carbon\Carbon;
use App\Models\Transaction;

class PartnerTransactionController extends Controller
{
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
            $cardUID,
            $staff,
            $validatedData['purchase_amount'],
            null,
            $image,
            $validatedData['note'],
            false
        );

        // Return the transaction details in a JSON response
        return response()->json($transaction);
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

     public function alltransactions(string $locale,Request $request){
         
        $partner = $request->user('partner_api');
        $perPage = $request->get('per_page', 10);

        
        $query = Transaction::query()
    ->where('created_by', $partner->id)
    ->orderBy('created_at', 'desc')
    ->select('id', 'created_at', 'purchase_amount', 'points', 'note', 'member_id', 'deleted_at')
    ->with([
        'member' => function ($query) {
            $query->select('id', 'unique_identifier', 'email');
        }
    ]);

    switch ($request->get('status')) {
            case 'success':
                // No changes needed; query remains as is
                break;

            case 'cancelled':
                $query->onlyTrashed();
                break;

            default:
                $query->withTrashed();
                break;
    }

// Paginate the results
  $data = $query->paginate($perPage);


        
            return response()->json($data);
        
     }
     public function cancel_transaction(string $locale,$tran_id, Request $request){

        $partner = $request->user('partner_api');

        $transaction = Transaction::findOrFail($tran_id); 

        if(!$transaction) {
            return response()->json(['message' => 'No Transaction found'], 404);
        }

        $transaction->deleted_by = $partner->id; 
        $transaction->save();
        
        $transaction->delete();

        return response()->json(['id' => $tran_id,'deleted_at'=>$transaction->deleted_at], 200);
     }
     public function findmember(string $locale,string $memberUID, Request $request){

        $partner = $request->user('partner_api');
        

        //return $partner;
        $member =Member::where('unique_identifier',$memberUID)->first();
        if(!$member) {
            return response()->json(['message' => 'Member not found'], 404);
        }
        else{
            $card_id="248378951208960";
             $cardUID="879-645-606-742";
             $card_name="KB Tier Card";

             $card = Card::findOrFail($card_id);

             $points = $card->getMemberBalance($member);


             if($partner->currency=='QAR'){
                $amount=((int)$points)/100;
             }
           
            return response()->json([
                'member' => $member,
                'points'=>$points,
                'amount'=>$amount,
                'currency'=>$partner->currency,
                'card_id'=>$card_id,
                'cardUID'=>$cardUID,
                'card_name'=>$card_name
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
    $validatedData['points'], 
    $memberUID, 
    $staff,  
    $request->image, 
    $request->note
);


// Return the transaction details in a JSON response
return response()->json($transaction);

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
        
        $member=Member::where('unique_identifier',$memberUID)->first();

        if(!$member) {
            return response()->json(['message' => 'Member not found'], 404);
        }
        
            
        
        $data = Transaction::where('member_id', $member->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->select('id', 'created_at', 'purchase_amount', 'points', 'note', 'staff_id', 'card_id')
            ->with([
                'staff' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'card' => function ($query) {
                    $query->select('id', 'name');
                },
            ])
            ->get();
        
        
    
       


        return response()->json(['data' => $data], 200);
     }
}
