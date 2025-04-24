<?php

namespace App\Http\Requests;

use App\Enums\RolesEnum;
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
        return [
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:moderator,user',
        ];
    }

    /**
     * Checks all required conditios to see if passed user is authorized to make this request.
     */
    private function userAuthorized(User $user): bool
    {
        if($user->role()->name === RolesEnum::ADMIN) {
            return true;
        } else {
            //teamrole na users tabeli ne na team_member
        }

        return true;
    }
}
