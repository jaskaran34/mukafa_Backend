<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PartnerStaffController extends Controller
{    
    /**
     * Retrieve all active staff members associated with the authenticated partner.
     *
     * @OA\Get(
     *     path="/{locale}/v1/partner/staff",
     *     operationId="getPartnerStaffMembers",
     *     tags={"Partner"},
     *     summary="Retrieve all active staff members of the authenticated partner",
     *     description="Fetch all active staff members where the authenticated partner has access to and the associated club is also active.",
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
     *         description="Staff members retrieved successfully",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/StaffMember"))
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
     * @return \Illuminate\Http\JsonResponse JSON response containing staff members details or error message
     */
    public function getStaff(string $locale, Request $request)
    {
        // Authenticate the partner using 'partner_api' guard
        $partner = $request->user('partner_api');

        // Fetch all active staff members linked to the partner, where their associated club is also active
        $staff = $partner->staff()
            ->where('is_active', 1)
            ->whereHas('club', function ($query) {
                $query->where('is_active', 1);
            })->get();

        // Hide sensitive data from each staff member before sending to the public
        $staff->each(function ($staffMember) {
            $staffMember->hideForPublic();
        });

        // Return the staff members details in a JSON response
        return response()->json($staff, 200);
    }

    /**
     * Retrieve a specific active staff member's details associated with the authenticated partner.
     *
     * @OA\Get(
     *     path="/{locale}/v1/partner/staff/{staffId}",
     *     operationId="getPartnerStaffMember",
     *     tags={"Partner"},
     *     summary="Retrieve a specific staff member's details of the authenticated partner",
     *     description="Fetch the details of a specific active staff member where the authenticated partner has access to and the associated club is also active.",
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
     *         name="staffId",
     *         in="path",
     *         description="Staff member's unique identifier",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Staff member details retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/StaffMember")
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
     *         description="Staff member not found",
     *         @OA\JsonContent(ref="#/components/schemas/NotFoundResponse")
     *     )
     * )
     *
     * @param Request $request The incoming HTTP request
     * @param string $locale The locale setting (e.g., 'en-us')
     * @param int $staffId The staff member's unique identifier
     * @return \Illuminate\Http\JsonResponse JSON response containing staff member details or error message
     */
    public function getStaffMember(string $locale, Request $request, int $staffId)
    {
        // Verify the partner using the 'partner_api' guard
        $partner = $request->user('partner_api');

        // Fetch the active staff member associated with the partner using staffId, where the associated club is also active
        $staffMember = $partner->staff()
            ->where('is_active', 1)
            ->whereHas('club', function ($query) {
                $query->where('is_active', 1);
            })->find($staffId);

        // If staff member not found, return a 404 response
        if (!$staffMember) {
            return response()->json(['message' => 'Staff member not found'], 404);
        }

        // Remove sensitive information before sending to the public
        $staffMember->hideForPublic();

        // Return the staff member details in a JSON response
        return response()->json($staffMember, 200);
    }
}
