<?php

namespace App\Http\Requests;

use App\Enums\RolesEnum;
use App\Enums\TeamRolesEnum;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserInviteRequest extends FormRequest
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
        $rules = [
            'email' => 'required|email|unique:users,email',
        ];
        
        if($this->user()->role->name === RolesEnum::ADMIN->value) {
            $rules['organization_id'] = 'required|exists:organizations,id';
        } else {
            $rules['team_role'] = 'required|in:' . implode(',', TeamRolesEnum::values());
        }

        return $rules;
    }
}
