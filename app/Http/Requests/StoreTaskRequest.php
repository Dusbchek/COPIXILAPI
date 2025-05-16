<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine si el usuario está autorizado para realizar esta solicitud.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
 // App\Http\Requests\StoreTaskRequest.php y UpdateTaskRequest.php

public function rules(): array
{
    return [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status_id' => 'nullable|exists:task_statuses,id',
        'due_date' => 'required|date',
        'user_ids' => 'nullable|array',
        'user_ids.*' => 'exists:users,id',
        'team_id' => 'nullable|exists:teams,id',
    ];
}


}
