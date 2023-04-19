<?php

namespace App\Http\Requests;

use App\Enums\TicketStatus;
use App\Http\Controllers\TicketController;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' =>       ['string','max:255'],
            'description' => ['string'],
            'status'    =>   ['string', Rule::in(array_column(TicketStatus::cases(), 'value'))],
            'attachment' => ['sometimes','file','mimes:jpg,jpeg,png,pdf'],
        ];
    }
}
