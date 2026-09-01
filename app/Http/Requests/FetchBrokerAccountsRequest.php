<?php

namespace App\Http\Requests;

use App\Enums\BrokersEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FetchBrokerAccountsRequest extends FormRequest
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
            'api_token' => ['required', 'string'],
            'broker_type' => [
                'required',
                Rule::enum(BrokersEnum::class),
            ],
        ];
    }
}
