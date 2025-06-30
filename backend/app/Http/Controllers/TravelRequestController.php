<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\TravelRequest;
use App\Enums\TravelResquestStatus;
use Illuminate\Validation\Rules\Enum;
use App\Http\Resources\TravelRequestResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreTravelRequestRequest;
use App\Notifications\TravelRequestStatusChange;

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

    public function update(Request $request, TravelRequest $travelRequest)
    {
        $this->authorize('update', TravelRequest::class);
        /** @var User $user */
        $user = auth('api')->user();

        $status = $request->validate([
            'status' => ['string', new Enum(TravelResquestStatus::class)]
        ])['status'];

        if ($travelRequest->status === TravelResquestStatus::APPROVED->value && $status !== TravelResquestStatus::APPROVED->value) {
            return response()->json([
                'error' => 'Its not possible cancel an accepted travel order!'
            ], Response::HTTP_CONFLICT);
        }

        $travelRequest->status = $status;
        $travelRequest->save();

        $user->notify(new TravelRequestStatusChange($user, $status, $travelRequest));

        return response()->json([
            'message' => 'Status update successful.',
            'data' => new TravelRequestResource($travelRequest)
        ]);
    }
}
