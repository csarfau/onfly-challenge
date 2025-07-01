<?php

namespace App\Http\Requests;

use App\Models\TravelRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreTravelRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', TravelRequest::class);
    }

    public function rules(): array
    {
        return [
            'requester_name' => ['required', 'max:255'],
            'destination' => ['required', 'max:255'],
            'departure_date' => ['required', 'after_or_equal:today', 'date'],
            'return_date' => ['required', 'after_or_equal:departure_date', 'date'],
        ];
    }
}
