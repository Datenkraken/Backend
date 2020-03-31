<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request that validates input for updating the retention policy.
 */
class UpdateRetentionPolicy extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'enableGlobalRetention' => 'boolean',
            'enableDefaultRetention' => 'boolean',
            'globalRetentionDate' => 'required_if:enableGlobalRetention,true|nullable|date',
            'defaultRetentionDays' => 'required_if:enableDefaultRetention,true|nullable|integer',
        ];
    }
}
