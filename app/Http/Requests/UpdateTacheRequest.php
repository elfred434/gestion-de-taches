<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTacheRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can(
            'update',
            $this->route('tache')
        ) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'min:3', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'terminee' => ['boolean'],
        ];
    }
    public function messages(): array
    {
        return [
            'titre.required' => 'le titre est obligatoire',
            'titre.string' => 'le titre doit être composé de caractères',
            'titre.min' => 'le titre doit au mins contenir :min caractères',
            'titre.max' => 'le titre ne doit dépasser :max caractères',
            'description.string' => 'la description doit être compsé de caractères',
            'description.max' => 'la description ne doit dépassé :max caractères',
            'terminee.boolean' => 'l\'état de la tâche est invalide. ',
        ];
    }
}
