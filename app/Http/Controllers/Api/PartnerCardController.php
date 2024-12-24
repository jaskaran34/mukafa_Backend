<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Card\CardService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PartnerCardController extends Controller
{
    /**
     * Retrieve all active cards where the authenticated partner has access to.
     *
     * @OA\Get(
     *     path="/{locale}/v1/partner/cards",
     *     operationId="getPartnerCards",
     *     tags={"Partner"},
     *     summary="Retrieve all active cards for the authenticated partner",
     *     description="Retrieve all active cards associated with the authenticated partner where the associated club is also active.",
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
     *     @OA\Response(
     *         response=200,
     *         description="Cards retrieved successfully",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Card"))
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized access",
     *         @OA\JsonContent(ref="#/components/schemas/UnauthenticatedResponse")
     *     )
     * )
     *
     * @param string $locale Locale setting (e.g., 'en-us')
     * @param Request $request The incoming HTTP request
     * @param CardService $cardService Service to handle card-related operations
     * @return Response JSON response containing card details or error message
     */
    public function getCards(string $locale, Request $request, CardService $cardService): Response
    {
        // Authenticate the partner using 'partner_api' guard
        $partner = $request->user('partner_api');
        
        // Get the authenticated partner's ID
        $partnerId = $partner->id;

        // Use the CardService to fetch all active cards associated with the partner
        $cards = $cardService->findActiveCardsFromPartner($partnerId, $hideColumnsForPublic = true);

        // Add the balance attribute with a value of -1 to all cards
        $cards->each(function ($card) {
            $card->balance = -1;
        });

        // Return the card details in a JSON response
        return response()->json($cards);
    }

    /**
     * Retrieve a specific active card by its ID where the authenticated partner has access to.
     *
     * @OA\Get(
     *     path="/{locale}/v1/partner/cards/{cardId}",
     *     operationId="getCard",
     *     tags={"Partner"},
     *     summary="Retrieve a specific active card for the authenticated partner",
     *     description="Retrieve a specific active card by its ID associated with the authenticated partner.",
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
     *         name="cardId",
     *         in="path",
     *         description="Unique identifier of the card to be retrieved",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Card details retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Card")
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
     *     )
     * )
     *
     * @param string $locale Locale setting (e.g., 'en-us')
     * @param Request $request The incoming HTTP request
     * @param CardService $cardService Service to handle card related operations
     * @param int $cardId Unique identifier of the card
     * @return Response JSON response containing card details or error message
     */
    public function getCard(string $locale, int $cardId, Request $request, CardService $cardService): Response
    {
        // Authenticate the partner using 'partner_api' guard
        $partner = $request->user('partner_api');

        // Retrieve the active card owned by the partner using the CardService
        $card = $cardService->findActiveCard($cardId, $authUserIsOwner = true, $guardUserIsOwner = 'partner_api', $hideColumnsForPublic = true);

        // If no card is found, return a 404 response
        if(!$card) {
            return response()->json(['message' => 'Card not found'], 404);
        }

        // Add the balance attribute with a value of -1
        $card->balance = -1;

        // Return the card details in a JSON response
        return response()->json($card);
    }
}
