<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PartnerAuthController extends Controller
{
    /**
     * Authenticate and log in a partner.
     *
     * @OA\Post(
     *     path="/{locale}/v1/partner/login",
     *     operationId="loginPartner",
     *     tags={"Partner"},
     *     summary="Authenticate and log in a partner",
     *     description="Authenticate a partner using their email and password and log them in.",
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
     *         description="The email of the partner",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="The password of the partner",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/PartnerLoginSuccess")
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     * 
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     ),
     *
     *     security={
     *         {"partner_auth_token": {}}
     *     }
     * )
     */

     public function sendWhatsapp($to)
     {
         $curl = curl_init();
     
         $payload = json_encode([
             "messages" => [
                 [
                     "from" => "447860099299",
                     "to" => $to,
                     "messageId" => "0b6e8b15-6c93-4815-8966-4a9d330395b9",
                     "content" => [
                         "templateName" => "test_whatsapp_template_en",
                         "templateData" => [
                             "body" => [
                                 "placeholders" => ["Jaskaran Singh"]
                             ]
                         ],
                         "language" => "en"
                     ]
                 ]
             ]
         ]);
     
         curl_setopt_array($curl, [
             CURLOPT_URL => 'https://v3xree.api.infobip.com/whatsapp/1/message/template',
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_ENCODING => '',
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 0,
             CURLOPT_FOLLOWLOCATION => true,
             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             CURLOPT_CUSTOMREQUEST => 'POST',
             CURLOPT_POSTFIELDS => $payload,
             CURLOPT_HTTPHEADER => [
                 'Authorization: App b16dbd5ab7bf6a87c5c112a84aae284a-d6f4fa39-8d2d-4030-ac9d-3ae2ed37d574',
                 'Content-Type: application/json',
                 'Accept: application/json'
             ],
         ]);
     
         $response = curl_exec($curl);
         curl_close($curl);
     
     }
     
     

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
     
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:96',
            'password' => 'required|min:6|max:48',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('partner')->attempt($credentials)) {
            $user = Auth::guard('partner')->user();
            $token =  $user->createToken('PartnerAPIToken')->plainTextToken;

$html="<!DOCTYPE html><html lang=\"en\"><head><meta charset=\"UTF-8\"><meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"><style>body {font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;} .container {max-width: 600px; margin: 20px auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);} .header {background-color: #4CAF50; color: #ffffff; padding: 10px 0; text-align: center; border-radius: 8px 8px 0 0;} .content {padding: 20px;} .message {padding: 10px; text-align: center; font-size: 18px; font-weight: bold; border-radius: 4px;}</style></head><body><div class=\"container\"><div class=\"header\"><h1>Login Successful!</h1></div><div class=\"content\"><p>Dear User,</p><p>We are excited to let you know that your login was successful! Welcome back to Mukafa.</p><p>Thank you,</p><p>Mukafa</p></div><div class=\"footer\">&copy; 2025 Mukafa. All rights reserved.</div></div></body></html>";

$this->sendCustomEmail('Login','jaskaran9056@gmail.com',$html);

$this->sendwhatsapp('917889481714');



            return response()->json(['token' => $token], 200);
        } else {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
    }

    /**
     * Log out the authenticated partner.
     *
     * @OA\Post(
     *     path="/{locale}/v1/partner/logout",
     *     operationId="logoutPartner",
     *     tags={"Partner"},
     *     summary="Log out the authenticated partner",
     *     description="Revoke all access tokens for the authenticated partner and log them out.",
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
     *         description="Partner logged out successfully",
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *
     *     security={
     *         {"partner_auth_token": {}}
     *     }
     * )
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Retrieve partner
        $partner = $request->user('partner_api');

        // Revoke all tokens
        $partner->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    /**
     * Retrieve the authenticated partner's data.
     *
     * @OA\Get(
     *     path="/{locale}/v1/partner",
     *     operationId="getPartner",
     *     tags={"Partner"},
     *     summary="Retrieve authenticated partner's data",
     *     description="Retrieve the data of the authenticated partner.",
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
     *         description="Partner data retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Partner")
     *     ),
     * 
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(ref="#/components/schemas/UnauthenticatedResponse")
     *     ),
     *
     *     security={
     *         {"partner_auth_token": {}}
     *     }
     * )
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPartner(Request $request)
    {
        // Retrieve partner
        $partner = $request->user('partner_api');

        // Hide sensitive information before exposing data
        $partner->hideForPublic();

        $data_partner=[
            'id'=> $partner->id,
            'name'=> $partner->name,
            'email'=> $partner->email,
            'phone_prefix'=> $partner->phone_prefix,

            'phone'=> $partner->phone,
            'created_at'=> $partner->created_at,
            'last_updated'=> $partner->updated_at,
            'avatar'=> $partner->avatar,
        ];

        return response()->json($data_partner, 200);
    }
}
