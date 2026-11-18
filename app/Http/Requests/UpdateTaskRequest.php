<?php

namespace App\Http\Requests;

use App\UserRole;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $task = $this->route('task');

        if ($user->role === UserRole::SUPERVISOR) {
            return $task->created_by === $user->id;
        }

        if ($user->role === UserRole::EMPLOYEE) {
            return $task->assigned_to === $user->id;
        }

        return false;    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->user()->role === UserRole::EMPLOYEE) {
        return [
            'status' => [
                'required',
                Rule::in(['pending', 'in_progress', 'completed']),
            ],
            'completion_summary' => [
            'required_if:status,completed',
            'nullable',
            'string',
            'max:5000',
        ],
        ];
    }

    if ($this->user()->role === UserRole::SUPERVISOR) {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],

            'description' => ['sometimes', 'nullable', 'string'],

            'status' => [
                'sometimes',
                'required',
                Rule::in(['pending', 'in_progress', 'completed']),
            ],

            'assigned_to' => [
                'sometimes',
                'required',
                'uuid',
                Rule::exists('users', 'id')
                    ->where('role', UserRole::EMPLOYEE->value),
            ],

            'due_date' => [
                'sometimes',
                'nullable',
                'date',
                'after_or_equal:today',
            ],
        ];
    }

    return [];
    }
}
