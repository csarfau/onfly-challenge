<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTravelRequestRequest;
use App\Models\TravelRequest;
use Carbon\Carbon;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class TravelRequestController extends Controller
{
    public function store(StoreTravelRequestRequest $request)
    {
        $newTravelRequest = $request->validated();
        $newTravelRequest['user_id'] = $request->user()->id;
        $newTravelRequest['departure_date'] = Carbon::createFromFormat('d/m/Y', $newTravelRequest['departure_date']);
        $newTravelRequest['return_date'] = Carbon::createFromFormat('d/m/Y', $newTravelRequest['return_date']);

        try {
            $createdTravelRequest = TravelRequest::create($newTravelRequest);
            return response()->json([
                'message' => 'Travel request created successful!',
                'data' => $createdTravelRequest
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to create a travel request.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
