<?php

namespace App\Http\Requests\Participants\Additionally;

use Illuminate\Foundation\Http\FormRequest;

require_once dirname(__DIR__, 4) . '/Domain/Constants/FieldConst.php';

class ParticipantSkippedRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            FIELD_TOURNAMENT_VALUE_ID   => ['required', 'numeric'],
            FIELD_USER_ID               => ['nullable', 'numeric'],
            FIELD_EVENT_KEY             => ['required', 'string'],
            FIELD_STAGE                 => ['required', 'numeric'],
            FIELD_ADMIN_ID              => ['required', 'numeric'],
        ];
    }
}
