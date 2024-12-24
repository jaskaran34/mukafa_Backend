<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Partner\PartnerService;
use Illuminate\Http\Request;
use App\Models\Partner;

class AdminPartnerController extends Controller
{
    protected $partnerService;

    public function __construct(PartnerService $partnerService)
    {
        $this->partnerService = $partnerService;
    }

    /**
     * Retrieve all accessible partners for the authenticated administrator.
     *
     * @OA\Get(
     *     path="/{locale}/v1/admin/partners",
     *     summary="Retrieve accessible partners to which the authenticated administrator has access",
     *     description="Allows the authenticated administrator to fetch a list of partners they have access to.",
     *     tags={"Admin"},
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
     *     @OA\Response(
     *         response=200,
     *         description="List of partners",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/AdminPartner"))
     *     ),
     *     security={
     *         {"admin_auth_token": {}}
     *     }
     * )
     * 
     * @param string $locale Locale setting (e.g., 'en-us')
     * @param Request $request The incoming HTTP request instance.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPartners(string $locale, Request $request)
    {
        $admin = $request->user('admin_api');

        if ($admin->role == 1) {
            $partners = Partner::all();
        } else {
            $networks = $admin->networks;
            $partners = collect();
            foreach ($networks as $network) {
                $partners = $partners->concat($network->partners);
            }
        }
    
        // Hide sensitive information for each partner
        $partners->each(function ($partner) {
            $partner->hideForPublic();
        });
    
        return response()->json($partners);
    }    

    /**
     * Retrieve a specific partner's details by ID accessible to the authenticated administrator.
     *
     * @OA\Get(
     *     path="/{locale}/v1/admin/partner/{partnerId}",
     *     summary="Retrieve a specific partner's details to which the authenticated administrator has access",
     *     description="Allows the authenticated administrator to fetch details of a specific partner by its ID.",
     *     tags={"Admin"},
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
     *         name="partnerId",
     *         in="path",
     *         required=true,
     *         description="ID of the partner to fetch",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Partner details",
     *         @OA\JsonContent(ref="#/components/schemas/AdminPartner")
     *     ),
     *     security={
     *         {"admin_auth_token": {}}
     *     }
     * )
     * 
     * @param string $locale Locale setting (e.g., 'en-us')
     * @param Request $request The incoming HTTP request instance.
     * @param int $partnerId ID of the partner to fetch.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPartner(string $locale, Request $request, $partnerId)
    {
        $admin = $request->user('admin_api');
    
        $partner = Partner::find($partnerId);
        if (!$partner) {
            return response()->json(['error' => 'Partner not found'], 404);
        }
    
        // Check if the admin has permission to get this partner
        if ($admin->role != 1) {
            $networks = $admin->networks;
            $hasPermission = false;
            foreach ($networks as $network) {
                if ($network->partners->contains($partner)) {
                    $hasPermission = true;
                    break;
                }
            }
            if (!$hasPermission) {
                return response()->json(['error' => 'Permission denied'], 403);
            }
        }
    
        // Hide sensitive information before exposing data
        $partner->hideForPublic();
    
        return response()->json($partner);
    }    

    /**
     * Update the details of a specific partner by ID accessible to the authenticated administrator.
     *
     * @OA\Put(
     *     path="/{locale}/v1/admin/partner/{partnerId}",
     *     summary="Update a specific partner by ID to which the authenticated administrator has access",
     *     description="Allows the authenticated administrator to update details of a specific partner by its ID.",
     *     tags={"Admin"},
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
     *         name="partnerId",
     *         in="path",
     *         required=true,
     *         description="ID of the partner to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Partner data to update",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", description="Partner's name", example="Partner Name"),
     *             @OA\Property(property="email", type="string", description="Partner's email", example="partner@example.com"),
     *             @OA\Property(property="locale", type="string", description="Locale setting", example="en-us"),
     *             @OA\Property(property="currency", type="string", description="Currency setting", example="USD"),
     *             @OA\Property(property="time_zone", type="string", description="Time zone setting", example="America/Los_Angeles"),
     *             @OA\Property(property="is_active", type="boolean", description="Active status of the partner", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated partner details",
     *         @OA\JsonContent(ref="#/components/schemas/AdminPartner")
     *     ),
     *     security={
     *         {"admin_auth_token": {}}
     *     }
     * )
     * 
     * @param string $locale Locale setting (e.g., 'en-us')
     * @param Request $request The incoming HTTP request instance.
     * @param int $partnerId ID of the partner to update.
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePartner(string $locale, Request $request, $partnerId)
    {
        $admin = $request->user('admin_api');
    
        $partner = Partner::find($partnerId);
        if (!$partner) {
            return response()->json(['error' => 'Partner not found'], 404);
        }
    
        // Check if the admin has permission to update this partner
        if ($admin->role != 1) {
            $networks = $admin->networks;
            $hasPermission = false;
            foreach ($networks as $network) {
                if ($network->partners->contains($partner)) {
                    $hasPermission = true;
                    break;
                }
            }
            if (!$hasPermission) {
                return response()->json(['error' => 'Permission denied'], 403);
            }
        }
    
        $data = $request->only(['name', 'email', 'locale', 'currency', 'time_zone', 'is_active']);
        $this->partnerService->update($partner, $data);
    
        // Hide sensitive information before exposing data
        $partner->hideForPublic();
    
        return response()->json($partner);
    }    
}
