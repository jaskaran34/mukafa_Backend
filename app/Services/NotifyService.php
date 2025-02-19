<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotifyService
{
    
    public function getHtml($type,$data=[])
    {
        
        if($type=='welcome_email_to_customer'){
            $title='Welcome to Mukafa';
           $content='<div class="content">
                <p>Dear '.$data['name'].',</p>
                <p>We are thrilled to inform you that your registration with Mukafa has been successfully completed! Welcome aboard.</p>
                <p><strong>Here are your account details:</strong></p>
                <p>
                    <strong>Mukafa Number:</strong> '.$data["mukafa_number"].'<br>
                    <strong>Tier:</strong> '.$data["card_name"].'<br>
                    <strong>Registered Mobile Number:</strong> '.$data["phone_prefix"].$data["phone"].'<br>
                    <strong>Email:</strong> '.$data["email"].'<br>
                    <strong>Birthday:</strong> '.$data["birthday"].'<br>';

                    if (!empty($data["anniversary_date"]) && $data["anniversary_date"] !== null) {
                        $content .= '<strong>Anniversary:</strong> ' . htmlspecialchars($data["anniversary_date"]) . '<br>';
                    }

                   $content .= '</p>
                
                <p>As a token of our appreciation, 500 mukafa points have been credited to your account as a welcome bonus. These points are equivalent to 5 QAR.</p>
                <p><strong>Your Mukafa points balance is:</strong> '.$data["balance"].'</p>
                <p>Thank you for joining Mukafa. We look forward to serving you!</p>
                <p>Best regards,</p>
                <p><strong>The Mukafa Team</strong></p>
</div>';

                }
                else if($type=='send_otp'){
                    $title='One Time Password';

                    if($data[2]=='issue')
                    {
                        $request_type='<b> Add Purchase to  your Mukafa account ('.$data[1].') </b>';
                    }
                    else if($data[2]=='redeem')
                    {
                        $request_type='<b>Redeem Mukafa Points </b>';
                    } 
                    $content = '<div class="content">
                    <p>Dear ' . htmlspecialchars($data[4]) . ',</p>
                    <p>Your OTP to '.$request_type.' is: <strong style="color: #007bff;">' . $data[3] . '</strong>.</p>
                    <p>This OTP is valid for 15 minutes. Please do not share it with anyone.</p>
                    <p>Best regards,</p>
                    <p><strong>The Mukafa Team</strong></p>
                    </div>';
                    }
                else if($type == 'purchase_email') {
                    $title = 'Purchase Confirmation';
                    $content = '<div class="content">
                                <p>Dear '.$data['customer_name'].'</p>
                                <p>Thank you for your recent purchase with Mukafa!</p>
                                <p><strong>Here are your purchase details:</strong></p>
                                <p>
                                    <strong>Order ID:</strong> ' . $data["order_id"] . '<br>
                                    <strong>Mukafa Number:</strong> ' . $data["mukafa_number"] . '<br>
                                    <strong>Tier:</strong> ' . $data["card_name"] . '<br>
                                    <strong>Purchase Amount:</strong> ' . $data["purchase_amount"] . '<br>
                                    <strong>Points Earned:</strong> ' . $data["mukafa_points"] . '<br>
                                    <strong>Status:</strong> ' . $data["status"] . '<br>
                                    
                                    <strong>Purchase Date:</strong> ' . $data["created_at"] . '<br><br>

                                    <strong>Current Balance:</strong> ' . $data["balance"] . ' Mukafa Points<br>
                                    <strong>Pending Balance:</strong> ' . $data["pending_points"] . ' Mukafa Points<br><br>
                                    These Points will be Credited to your Mukafa Account By ' . $data["credit_to_account_by"] . ' .Also you can choose to cancel this transaction before that timeline<br>
                                </p>
                                
            
                                <p>Best regards,</p>
                                <p><strong>The Mukafa Team</strong></p>
                                </div>';
                }
                else if($type == 'redeem_email') {
                    $title = 'Redemption Confirmation';
                    $content = '<div class="content">
                                <p>Dear '.$data['customer_name'].'</p>
                                <p>Congratulations on your recent redemption with Mukafa!</p>
                                <p>We are thrilled to inform you that your redemption has been successfully processed.</p>
                                 <p><strong>Here are the details:</strong></p>
                                <p>
                                    <strong>Order ID:</strong> ' . $data["order_id"] . '<br>
                                    <strong>Mukafa Number:</strong> ' . $data["mukafa_number"] . '<br>
                                    <strong>Tier:</strong> ' . $data["card_name"] . '<br>
                                    <strong>Points Redeemed:</strong> ' . $data["mukafa_points"] . '<br>
                                    <strong>Status:</strong> ' . $data["status"] . '<br>
                                    <strong>Redemption Date:</strong> ' . $data["created_at"] . '<br><br>

                                    <strong>Current Balance:</strong> ' . $data["balance"] . ' Mukafa Points<br>
                                    <strong>Pending Balance:</strong> ' . $data["pending_points"] . ' Mukafa Points<br><br>
                                    
                                </p>
                                
            
                                <p>Best regards,</p>
                                <p><strong>The Mukafa Team</strong></p>
                                </div>';
                }
                else if($type=='cancel_tran'){

                    $title='One Time Password';

                    $content = '<div class="content">
            <p>Dear Admin,</p>
            <p>Your OTP to cancel the transaction is: <strong style="color: #007bff;">' . htmlspecialchars($data[3]) . '</strong>.</p>
            <p>Transaction Details:</p>
            <ul>
                <li><strong>Transaction ID:</strong> ' . $data[6] . '</li>
                <li><strong>Mukafa Account No.:</strong> ' . $data[1] . '</li>
                <li><strong>Account Holder:</strong> ' .$data[4] . '</li>
                <li><strong>Purchase Amount:</strong> '.$data[5].'</li>
            </ul>
            <p>This OTP is valid for the next 15 minutes. Please do not share it with anyone.</p>
            <p>Best regards,</p>
            <p><strong>The Mukafa Team</strong></p>
            </div>';
                
                }
                else if($type=='tran_cancel_confirm'){

                    $title='Confirmation: Transaction Cancelled';

                    $dataArray = json_decode($data, true);

                    if($dataArray['cancel_type']=='F'){
                        $cancel_type='Completely';
                    }
                    else{
                        $cancel_type='Partially';
                    }

                    $content = '<div class="content">
            <p>Dear Admin,</p>
            <p>Transaction Id: <strong style="color: #007bff;">' . htmlspecialchars($dataArray['refrence_tran_id']) . '</strong>. Cancelled '.$cancel_type.' at '.$dataArray['cancel_time'].'</p>
            <p>Cancellation Details:</p>
            <ul>
                <li><strong>Cancellation ID:</strong> ' . $dataArray['cancel_tran_id'] . '</li>
                <li><strong>Mukafa Account No.:</strong> ' . $dataArray['mukafa_number'] . '</li>
                <li><strong>Points Returned:</strong> '.$dataArray['points_returned'].'</li>
                <li><strong>Balance:</strong> '.$dataArray['balance'].' Mukafa Points</li>
                <li><strong>Pending Balance:</strong> '.$dataArray['pending_points'].' Mukafa Points</li>
            </ul>
            
            <p>Best regards,</p>
            <p><strong>The Mukafa Team</strong></p>
            </div>';
                
                }
                else if($type=='tier_change'){
                    $title='Confirmation: Tier Changed';
                    $welcome_bonus="";
                    if($data[3] > $data[2]){
                        if($data[6]=='Y'){
                            $welcome_bonus=$data[7]." Points as Welcome Bonus have been credited to your mukafa account";  
                        }
                    $stage="upgraded";
                    }
                    else{
                        $stage='downgraded';
                    }

                    $content = '<div class="content">
            <p>Dear '.$data[0].',</p>
            <p>Mukafa Account No.: <strong style="color: #007bff;">'.$data[1].'</strong>. has been '.$stage.' to the '.$data[4].' tier. '.$welcome_bonus.'</p>
            <p>Best regards,</p>
            <p><strong>The Mukafa Team</strong></p>
            </div>';

                }
                
                
        $html = '<!DOCTYPE html>
    <html lang="en">
    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }
            .container {
                max-width: 600px;
                margin: 20px auto;
                background-color: #ffffff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            .header {
                background-color: #fbebec;
                color: #c93062;
                padding: 10px 0;
                text-align: center;
                border-radius: 8px 8px 0 0;
            }
            .content {
                padding: 20px;
            }
            .footer {
                text-align: center;
                font-size: 12px;
                color: #666;
                padding: 10px;
                border-top: 1px solid #ddd;
                margin-top: 20px;
            }
        </style>
    </head>
    
    <body>
        <div class="container">
            <div class="header">
                <h1>'.$title.'</h1>
            </div>
            '.$content.'

            <div class="footer">
                &copy; 2025 Mukafa. All rights reserved.
            </div>
        </div>
    </body>
    
    </html>';

        return $html;
        
    }
    
    public function payload($type_of_msg, $to, $messageId)
    {
        if ($type_of_msg == 'auth_otp') {
            $otp = rand(100000, 999999);
            $payload = json_encode([
                "messages" => [
                    [
                        "from" => "15557345242",
                        "to" => $to,
                        "messageId" => $messageId,
                        "content" => [
                            "templateName" => "otp_temp",
                            "templateData" => [
                                "body" => [
                                    "placeholders" => [$otp]
                                ],
                                "buttons" => [
                                    [
                                        "type" => "URL",
                                        "parameter" => "6666"
                                    ]
                                ]
                            ],
                            "language" => "en_GB"
                        ]
                    ]
                ]
            ]);
            return $payload;
        }

        return null;
    }

    public function sendWhatsapp($type_of_msg, $to, $messageId)
    {
        $payload = $this->payload($type_of_msg, $to, $messageId);

        if (!$payload) {
            return null;
        }

        $response = Http::withHeaders([
            'Authorization' => 'App ' . env('INFOBIP_API_TOKEN'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->post('https://v3xree.api.infobip.com/whatsapp/1/message/template', json_decode($payload, true));

        return $response->json();
    }


public function sendCustomEmail($email_to,$type,$data=[])
{
   if($type=='welcome_email_to_customer'){
    $subject='User Registerd';
    $html=$this->getHtml($type,$data);
    }
    else if($type=='purchase_email'){
        $subject='Purchase Successful';
        $html=$this->getHtml($type,$data);
        }
        else if($type=='redeem_email'){
            $subject='Redemption Successful';
            $html=$this->getHtml($type,$data);
            }
        else if($type=='send_otp'){
            $subject='OTP Verification';
            $html=$this->getHtml($type,$data);
            }
            else if($type=='cancel_tran'){
                $subject='Cancel Transaction';
                $html=$this->getHtml($type,$data);
                }
            else if($type=='tran_cancel_confirm'){
                $subject='Transaction Cancelled';
                $html=$this->getHtml($type,$data);
            }
            else if($type=='tier_change'){
                $subject='Tier Changed';
                $html=$this->getHtml($type,$data);
            }
            
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . env('MAILTRAP_TOKEN'),
        'Content-Type' => 'application/json',
    ])->post('https://send.api.mailtrap.io/api/send', [
        "from" => [
            "email" => "hello@js.qa",
            "name" => "Mukafa"
        ],
        "to" => [$email_to],
        "subject" => $subject,
        "html" => $html
    ]);

    return $response->json();
}

public function send_sms($mobile,$msg)
    {
        
        $baseUrl = env('OOREDOO_BASE_URL', 'https://messaging.ooredoo.qa/bms/soap/Messenger.asmx');
    $customerId = env('OOREDOO_CUSTOMER_ID', 809); // Customer ID from authentication
    $customerUsername = env('OOREDOO_USERNAME', 'jsouq'); // Customer username
    $language = 'en'; // Language
    $userPassword = env('OOREDOO_PASSWORD', 'Jsouq@2025$$'); // Correct password
    $originator = 'Jumbo Souq'; // Use one of the valid originators
    $recipientPhone = $mobile; // Replace with recipient phone number
    $smsText = $msg;
    $defDate = ''; // Leave empty if you want to send immediately
    $messageType = 'Latin'; // Set to 'text' for regular SMS
    $blink = 'false'; // Set to 'true' if blink SMS is required
    $flash = 'false'; // Set to 'true' if flash SMS is required
    $private = 'false'; // Set to 'true' if the message should be private

    // Construct the HTTP GET request
    $response = Http::get("$baseUrl/HTTP_SendSms", [
        'customerID' => $customerId,
        'userName' => $customerUsername,
        'userPassword' => $userPassword,
        'originator' => $originator,
        'smsText' => $smsText,
        'recipientPhone' => $recipientPhone,
        'defDate' => $defDate,
        'messageType' => $messageType,
        'blink' => $blink,
        'flash' => $flash,
        'Private' => $private,
    ]);

    Log::info($response);


}

}
