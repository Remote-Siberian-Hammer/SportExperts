<?php

namespace App\Http\Requests\Participants\Search;

use Illuminate\Foundation\Http\FormRequest;

require_once dirname(__DIR__, 4) . '/Domain/Constants/FieldConst.php';

class ParticipantSearchRequest extends FormRequest
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
            FIELD_EVENT_KEY         => ['required', 'string'],
            FIELD_FIRST_NAME        => ['nullable', 'min:2', 'max:255'],
            FIELD_FIRST_NAME_ENG    => ['nullable', 'min:2', 'max:255'],
            FIELD_LAST_NAME         => ['nullable', 'min:2', 'max:255'],
            FIELD_LAST_NAME_ENG     => ['nullable', 'min:2', 'max:255'],
            FIELD_BIRTH_DATE        => ['nullable'],
            FIELD_GENDER            => ['nullable'],
            FIELD_EMAIL             => ['nullable', 'min:8', 'max:255', 'unique:users'],
            FIELD_PHONE             => ['nullable', 'max:20', 'unique:users'],
            FIELD_WIDTH             => ['nullable', 'numeric'],
            FIELD_HEIGHT            => ['nullable',  'numeric'],
        ];
    }
}
