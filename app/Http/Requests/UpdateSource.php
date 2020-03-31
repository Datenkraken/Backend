<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request that validates input for updating a source.
 */
class UpdateSource extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required',
            'name' => 'required|string',
            'url' => 'required|active_url',
            'categories' => 'sometimes|array',
            'categories.*' => 'sometimes|required',
        ];
    }
}
