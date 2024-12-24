<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Services\Card\CardService;
use App\Services\Member\MemberService;
use App\Services\Card\TransactionService;
use App\Services\I18nService;
use Illuminate\Http\Request;

/**
 * Class TransactionController
 * @package App\Http\Controllers\Partner
 *
 * Handles transactions for members.
 */
class TransactionController extends Controller
{
    /**
     * Display the form to earn points.
     *
     * @param string $locale
     * @param string $member_identifier
     * @param string $card_identifier
     * @param Request $request
     * @param MemberService $memberService
     * @param CardService $cardService
     * @param I18nService $i18nService
     * @return \Illuminate\View\View
     */
    public function showTransactions(
        string $locale,
        string $member_identifier,
        string $card_identifier,
        Request $request,
        MemberService $memberService,
        CardService $cardService,
        I18nService $i18nService
    ): \Illuminate\View\View {
        $member = $memberService->findActiveByIdentifier($member_identifier);
        $card = $cardService->findActiveCardByIdentifier($card_identifier);
        if (!$card) abort(404);

        $currency = $i18nService->getCurrencyDetails($card->currency);

        return view('partner.transactions.transactions', compact('card', 'member', 'currency'));
    }

    /**
     * Delete the last transaction.
     *
     * @param string $locale
     * @param string|null $member_identifier
     * @param string|null $card_identifier
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteLastTransaction(string $locale, string $member_identifier = null, string $card_identifier = null, MemberService $memberService, CardService $cardService, TransactionService $transactionService)
    {
        $member = $memberService->findActiveByIdentifier($member_identifier);
        $card = $cardService->findActiveCardByIdentifier($card_identifier);
        if (!$card) abort(404);

        $partner = auth('partner')->user();
        $lastTransactionFound = $transactionService->deleteLastTransaction($partner, $member, $card);

        // Redirect back
        return redirect()->back()->with(($lastTransactionFound) ? 'success' : 'error', ($lastTransactionFound) ? trans('common.delete_last_transaction_success') : trans('common.delete_last_transaction_error'));
    }
}
