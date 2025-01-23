<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use App\Services\Member\MemberService;
use Carbon\Carbon;
use App\Notifications\Member\Registration;

use App\Models\Partner;
use App\Models\Card;
use App\Models\Transaction;

class MemberAuthController extends Controller
{
    /**
     * Register a new member.
     * 
     * @OA\Post(
     *     path="/{locale}/v1/member/register",
     *     operationId="registerMember",
     *     tags={"Member"},
     *     summary="Register a new member",
     *     description="Allows a new member to create an account by providing necessary details.",
     *     @OA\Parameter(
     *         name="locale",
     *         in="path",
     *         description="The locale (e.g., `en-us`)",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *           default="en-us"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="The email of the member",
     *         required=true,
     *         @OA\Schema(type="string", format="email", example="email@example.com")
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="The name of the member",
     *         required=true,
     *         @OA\Schema(type="string", maxLength=64, example="John Doe")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="The password of the member, if empty a password is generated",
     *         required=false,
     *         @OA\Schema(type="string", minLength=6, maxLength=48, example="password123")
     *     ),
     *     @OA\Parameter(
     *         name="time_zone",
     *         in="query",
     *         description="The time zone of the member",
     *         required=false,
     *         @OA\Schema(type="string", example="America/New_York")
     *     ),
     *     @OA\Parameter(
     *         name="locale",
     *         in="query",
     *         description="The locale of the member (e.g., `en_US`)",
     *         required=false,
     *         @OA\Schema(type="string", minLength=5, maxLength=12, example="en_US")
     *     ),
     *     @OA\Parameter(
     *         name="currency",
     *         in="query",
     *         description="The currency of the member (e.g., `USD`)",
     *         required=false,
     *         @OA\Schema(type="string", minLength=3, maxLength=3, example="USD")
     *     ),
     *     @OA\Parameter(
     *         name="accepts_emails",
     *         in="query",
     *         description="Whether the member accepts marketing emails from a partner or not",
     *         required=false,
     *         @OA\Schema(type="integer", enum={0, 1}, example=0)
     *     ),
     *     @OA\Parameter(
     *         name="send_mail",
     *         in="query",
     *         description="Whether to send an email with the password to the newly created member",
     *         required=false,
     *         @OA\Schema(type="integer", enum={0, 1}, example=0)
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="Member registration successful",
     *         @OA\JsonContent(ref="#/components/schemas/MemberRegistration")
     *     ),
     * 
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     )
     * )
     *
     * @param  Request  $request
     * @param  MemberService  $memberService
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request, MemberService $memberService)
    {

        
        
        // Validate request inputs
        $request->validate([
            'email' => 'required|email|max:96|unique:members',
            'phone' => ['required', 'regex:/^[0-9]{10}$/', 'unique:members'],
            'phone_prefix'=>'required|min:2|max:4',
            'name' => 'required|max:64',
            'password' => 'nullable|min:6|max:48',
            'time_zone' => 'nullable',
            'accepts_emails' => 'nullable|boolean',
            'send_mail' => 'nullable|boolean',
            'locale' => 'nullable|min:5|max:12',
            'currency' => 'nullable|min:3|max:3',
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
        $response=[
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'password' => $password
        ];
        $member['password'] = bcrypt($password);
    
        // 'send_mail' should not be stored in the database
        $member = Arr::except($member, ['send_mail']);
    
        // Save new member to database
        $newMember = $memberService->store($member);
    
        if($request->partner_id && $request->partner_id=='248216521760768'){
          
            $partner=Partner::findOrFail($request->partner_id);
            $staff=$partner->superadminstaff->first();
            
            //$cards=$partner->cards;
            $card=Card::where('id','248378951208960')->where('created_by',$partner->id)->first();

            $created_at =  Carbon::now('UTC');
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
                    'event' => 'initial_bonus_points',
                    'status' => 'completed',
                    'created_at' => $created_at,
                    'updated_at' => $created_at,
                ]);
                $transaction = Transaction::create($bonusData);
    
                
            }

        }



        // Send registration mail if requested
        if ((int) $send_mail === 1) {
            $newMember->notify(new Registration($member['email'], $password, 'member'));
        }
        $response['unique_identifier']=$newMember->unique_identifier; 
        // Return a response with member details
        return response()->json($response, 201);
    }

    /**
     * Authenticate and log in a member.
     *
     * @OA\Post(
     *     path="/{locale}/v1/member/login",
     *     operationId="loginMember",
     *     tags={"Member"},
     *     summary="Log in a member",
     *     description="Authenticates a member using their email and password.",
     *
     *     @OA\Parameter(
     *         name="locale",
     *         in="path",
     *         description="The locale (e.g. `en-us`)",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *           default="en-us"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="The email of the member",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="The password of the member",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/MemberLoginSuccess")
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     security={
     *         {"member_auth_token": {}}
     *     }
     * )
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:96',
            'password' => 'required|min:6|max:48',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('member')->attempt($credentials)) {
            $user = Auth::guard('member')->user();
            $token =  $user->createToken('MemberAPIToken')->plainTextToken;

            if ($user && $user->is_active == 1) {
                // Update login stats
                $user->email_verified_at = $user->email_verified_at ?? Carbon::now('UTC');
                $user->number_of_times_logged_in++;
                $user->last_login_at = Carbon::now('UTC');
                $user->save();
    
                return response()->json(['token' => $token], 200);
            } else {
                throw ValidationException::withMessages([
                    'email' => ['This member is not active.'],
                ]);
            }

        } else {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
    }

    /**
     * Log out the authenticated member.
     *
     * @OA\Post(
     *     path="/{locale}/v1/member/logout",
     *     operationId="logoutMember",
     *     tags={"Member"},
     *     summary="Log out a member",
     *     description="Revokes all access tokens for the authenticated member, effectively logging them out.",
     * 
     *     @OA\Parameter(
     *         name="locale",
     *         in="path",
     *         description="The locale (e.g., `en-us`)",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *           default="en-us"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Member logged out successfully",
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *
     *     security={
     *         {"member_auth_token": {}}
     *     }
     * )
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Retrieve member
        $member = $request->user('member_api');

        // Revoke all tokens
        $member->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    /**
     * Retrieve the authenticated member's data.
     *
     * @OA\Get(
     *     path="/{locale}/v1/member",
     *     operationId="getMember",
     *     tags={"Member"},
     *     summary="Retrieve member data",
     *     description="Fetches the data of the authenticated member.",
     * 
     *     @OA\Parameter(
     *         name="locale",
     *         in="path",
     *         description="The locale (e.g., `en-us`)",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *           default="en-us"
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="Member data retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Member")
     *     ),
     * 
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(ref="#/components/schemas/UnauthenticatedResponse")
     *     ),
     *
     *     security={
     *         {"member_auth_token": {}}
     *     }
     * )
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMember(Request $request)
    {
        // Retrieve member
        $member = $request->user('member_api');

        // Hide sensitive information before exposing data
        $member->hideForPublic();

        return response()->json($member, 200);
    }
}
