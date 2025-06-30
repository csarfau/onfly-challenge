<?php

namespace App\Http\Controllers;

use App\Enums\TravelResquestStatus;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\TravelRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreTravelRequestRequest;
use App\Http\Resources\TravelRequestResource;

class TravelRequestController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', TravelRequest::class);
        $departureDate = $request->input('departure_date');
        $returnDate = $request->input('return_date');
        $travelRequestStatus = TravelResquestStatus::tryFrom($request->input('status'))->value;

        $formattedDepartureDate = $departureDate
            ? Carbon::createFromFormat('d/m/Y', $departureDate)->format('Y-m-d')
            : null;

        $formattedReturnDate = $returnDate
            ? Carbon::createFromFormat('d/m/Y', $returnDate)->format('Y-m-d')
            : null;

        $travelRequestsQuery = $request->user()->isAdmin()
            ? TravelRequest::query()
            : $request->user()->travelRequests();

        $travelRequests = $travelRequestsQuery
            ->filterByStatus($travelRequestStatus)
            ->filterByPeriod($formattedDepartureDate, $formattedReturnDate)
            ->filterByDestination($request->destination)
            ->paginate(10);

        return response()->json(TravelRequestResource::collection($travelRequests), Response::HTTP_OK);
    }

    public function show(TravelRequest $travelRequest)
    {
        $this->authorize('view', $travelRequest);
        return response()->json(new TravelRequestResource($travelRequest), Response::HTTP_OK);
    }

    public function store(StoreTravelRequestRequest $request)
    {
        $newTravelRequest = $request->validated();
        $newTravelRequest['user_id'] = $request->user()->id;
        $newTravelRequest['status'] = TravelResquestStatus::REQUESTED->value;
        $newTravelRequest['departure_date'] = Carbon::createFromFormat('d/m/Y', $newTravelRequest['departure_date']);
        $newTravelRequest['return_date'] = Carbon::createFromFormat('d/m/Y', $newTravelRequest['return_date']);

        try {
            $createdTravelRequest = TravelRequest::create($newTravelRequest);
            return response()->json([
                'message' => 'Travel request created successful!',
                'data' => new TravelRequestResource($createdTravelRequest)
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to create a travel request.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
