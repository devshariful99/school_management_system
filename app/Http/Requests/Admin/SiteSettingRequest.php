<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SiteSettingRequest extends FormRequest
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
            'site_name' => 'required|sometimes|string|min:4',
            'site_short_name' => 'required|sometimes|string|min:2',
            'timezone' => 'required|sometimes',
            'site_logo' => 'required|sometimes',
            'site_favicon' => 'required|sometimes',
            'env' => 'required|sometimes|string',
            'debug' => 'required|sometimes|boolean',
            'debugbar' => 'required|sometimes|boolean',
            'audit' => 'required|sometimes|boolean',
            'date_format' => 'required|sometimes|string',
            'time_format' => 'required|sometimes|string',
            'sms_api_url' => 'required|sometimes|url',
            'sms_api_key' => 'required|sometimes',
            'sms_api_secret' => 'nullable',
            'sms_api_status_code' => 'required|sometimes',
            'sms_api_sender_id' => 'required|sometimes',

        ];
    }
}