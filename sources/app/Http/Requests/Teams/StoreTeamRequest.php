<?php

namespace App\Http\Requests\Teams;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamRequest extends FormRequest
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
            'users_id'      => ['required', 'numeric'],
            'key'           => ['required'],
            'name'          => ['required', 'string', 'min:6', 'max:255'],
            'description'   => ['required', 'string'],
            'image'         => ['required', 'string', 'max:255'],
            'location'      => ['required', 'string', 'max:255'],
        ];
    }
}
