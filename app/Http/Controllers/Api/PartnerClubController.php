<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PartnerClubController extends Controller
{    
    /**
    * Retrieve all active clubs where the authenticated partner has access to.
    *
    * @OA\Get(
    *     path="/{locale}/v1/partner/clubs",
    *     operationId="getPartnerClubs",
    *     tags={"Partner"},
    *     summary="Retrieve all active clubs for the authenticated partner",
    *     description="Retrieve all active clubs associated with the authenticated partner where the associated club is also active.",
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
    *         description="Clubs retrieved successfully",
    *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Club"))
    *     ),
    *     
    *     @OA\Response(
    *         response=401,
    *         description="Unauthorized access",
    *         @OA\JsonContent(ref="#/components/schemas/UnauthenticatedResponse")
    *     )
    * )
    *
    * @param Request $request The incoming HTTP request
    * @param string $locale The locale setting (e.g., 'en-us')
    * @return \Illuminate\Http\JsonResponse JSON response containing clubs details or error message
    */
    public function getClubs(string $locale, Request $request)
    {
        // Authenticate the partner using 'partner_api' guard
        $partner = $request->user('partner_api');

        // Fetch all active clubs linked to the partner
        $clubs = $partner->clubs()->where('is_active', 1)->get();

        // Hide sensitive data from each club before sending to the public
        $clubs->each(function ($club) {
            $club->hideForPublic();
        });

        // Return the clubs details in a JSON response
        return response()->json($clubs, 200);
    }

    /**
     * Retrieve a specific active club by its ID where the authenticated partner has access to.
     *
     * @OA\Get(
     *     path="/{locale}/v1/partner/clubs/{clubId}",
     *     operationId="getPartnerClub",
     *     tags={"Partner"},
     *     summary="Retrieve a specific active club for the authenticated partner by its ID",
     *     description="Retrieve the details of a specific active club identified by the clubId where the authenticated partner has access to.",
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
     *         name="clubId",
     *         in="path",
     *         description="Club's unique identifier",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Club details retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Club")
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
     *         description="Club not found",
     *         @OA\JsonContent(ref="#/components/schemas/NotFoundResponse")
     *     )
     * )
     *
     * @param Request $request The incoming HTTP request
     * @param string $locale The locale setting (e.g., 'en-us')
     * @param int $clubId The club's unique identifier
     * @return \Illuminate\Http\JsonResponse JSON response containing club details or error message
     */
    public function getClub(string $locale, Request $request, int $clubId)
    {
        // Verify the partner using the 'partner_api' guard
        $partner = $request->user('partner_api');

        // Fetch the active club associated with the partner using clubId
        $club = $partner->clubs()->where('is_active', 1)->find($clubId);

        // If club not found, return a 404 response
        if (!$club) {
            return response()->json(['message' => 'Club not found'], 404);
        }

        // Remove sensitive information before sending to the public
        $club->hideForPublic();

        // Return the club details in a JSON response
        return response()->json($club, 200);
    }
}
