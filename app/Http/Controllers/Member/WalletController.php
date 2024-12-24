<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Services\Card\CardService;
use App\Services\Member\WalletService;
use Illuminate\Http\Request;

/**
 * Class WalletController
 * @package App\Http\Controllers\Member
 *
 * Handles wallet for members.
 */
class WalletController extends Controller
{
    /**
     * Handles the request to display the wallet page.
     *
     * This method retrieves the wallet data, applies sorting and filtering
     * options from the request or from cookies, and returns the wallet view 
     * with attached cookies for the sorting and hide_expired filter.
     *
     * @param Request $request
     * @param CardService $cardService
     * @return \Illuminate\Http\Response
     */
    public function showWallet(Request $request, CardService $cardService): \Illuminate\Http\Response {
        // Define the allowed values for the sort parameter
        $allowedSortValues = [
            'last_points_claimed_at,desc',
            'last_points_claimed_at,asc',
            'total_amount_purchased,desc',
            'total_amount_purchased,asc',
            'number_of_rewards_claimed,desc',
            'number_of_rewards_claimed,asc',
            'last_reward_claimed_at,desc',
            'last_reward_claimed_at,asc'
        ];

        // Extract query parameters or get from cookies if they exist
        $sort = $request->query('sort', $request->cookie('wallet_sort', 'last_points_claimed_at,desc'));
        $hide_expired = $request->query('hide_expired', $request->cookie('wallet_hide_expired', 'true'));

        // Validate the 'sort' query parameter and reset it to default if it's not in the allowed sort values
        if (!in_array($sort, $allowedSortValues)) {
            $sort = 'last_points_claimed_at,desc';
        }
    
        // Validate the 'hide_expired' query parameter and reset it to default if it's not 'true' of 'false'
        if (!in_array($hide_expired, ['true', 'false'])) {
            $hide_expired = 'true';
        }
    
        // Convert hide_expired to a boolean
        $hide_expired = filter_var($hide_expired, FILTER_VALIDATE_BOOLEAN);
    
        // Extract the column and direction from the sort value
        [$column, $direction] = explode(',', $sort);
    
        // Retrieve cards from the authenticated member
        $memberId = auth('member')->user()->id;
    
        $cards = $cardService->findCardsWithMemberTransactions($memberId, $column, $direction, 'is_active', true, $hide_expired);
    
        // Prepare view
        $view = view('member.wallet.wallet', compact('cards', 'sort', 'hide_expired'));
    
        // Convert boolean values back to strings for the cookie
        $hide_expired = $hide_expired ? 'true' : 'false';
    
        // Create cookies for sort and hide_expired
        $sortCookie = cookie('wallet_sort', $sort, 43200, '/', null, false, true, false, 'lax');
        $hideExpiredCookie = cookie('wallet_hide_expired', $hide_expired, 43200, '/', null, false, true, false, 'lax');

        // Attach cookies to the response and return it
        $response = response($view)->withCookie($sortCookie)->withCookie($hideExpiredCookie);

        return $response;
    }
}
