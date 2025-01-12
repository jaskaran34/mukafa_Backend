<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Support\Facades\Exceptions;
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

public function register(Request $request){


    $request->validate([
        'email' => 'required|email|max:96|unique:staff',
        'name' => 'required|max:64',
        'password' => 'nullable|min:6|max:48',
    ]);



    $partner = $request->user('partner_api');

    $staff=Staff::create([
        'club_id' => $partner->clubs->first()->id,
        'name' => $request->name,
        'email' => $request->email,
        'meta'=> (int) $request->staff_type,
        'password' => bcrypt($request->password),
        'role' => 1, // 1 = user
        'email_verified_at' => Carbon::now('UTC'),
        'is_active' => true,
        'is_undeletable' => env('APP_IS_UNEDITABLE', true),
        'is_uneditable' => env('APP_IS_UNEDITABLE', true),
        'created_at' => Carbon::now('UTC'),
        'locale' => config('app.locale'),
        'currency' => 'QAR',
        'time_zone' => 'Asia/Qatar',
        'created_by' => $partner->id,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Staff registered successfully.',
        'data' => $staff,
    ], 201);
    

    }
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

    public function getsuperStaff(string $locale, Request $request)
    {
        // Verify the partner using the 'partner_api' guard
        $partner = $request->user('partner_api');

        // Fetch the active staff member associated with the partner using staffId, where the associated club is also active
        $staffMember =$partner->staff()->where('meta', 1)->first();

       

        // If staff member not found, return a 404 response
        if (!$staffMember) {
            return response()->json(['message' => 'Super-AdminStaff not found'], 404);
        }

        // Remove sensitive information before sending to the public
        $staffMember->hideForPublic();

        // Return the staff member details in a JSON response
        return response()->json($staffMember, 200);
    }
}
