<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request that validates input for adding a source.
 */
class AddSource extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'url' => 'required|active_url',
            'categories' => 'sometimes|array',
            'categories.*' => 'sometimes|required',
        ];
    }
}
