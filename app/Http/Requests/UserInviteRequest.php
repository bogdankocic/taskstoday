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
        return $this->userAuthorized($this->user());
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
            $rules['team_role'] = 'required|in:moderator,user';
        }

        return $rules;
    }

    /**
     * Checks all required conditios to see if passed user is authorized to make this request.
     */
    private function userAuthorized(User $user): bool
    {
        if($user->role->name === RolesEnum::ADMIN->value) {
            return true;
        } else {
            return $user->organization_id === $this->input('organization_id');
        }

        return true;
    }
}
