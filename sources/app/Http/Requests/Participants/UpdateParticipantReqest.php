<?php

namespace App\Http\Requests\Participants;

use Illuminate\Foundation\Http\FormRequest;

class UpdateParticipantReqest extends FormRequest
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
            'id'                => ['required', 'numeric'],
            'event_id'          => ['required', 'numeric', 'exists:event,id'],
            'user_id'           => ['required', 'numeric', 'exists:users,id'],
            'invited_user_id'   => ['required', 'exists:users,id'],
            'team_key'          => ['required', 'exists:team,key'],
        ];
    }
}
