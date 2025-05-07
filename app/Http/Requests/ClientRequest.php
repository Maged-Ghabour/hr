<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'client_phone' => 'required|unique:clients,client_phone,' . $this->route('record'),

        ];
    }

    public function store(ClientRequest $request)
    {
        // البيانات ستكون مُتحققة مع الرسائل المخصصة
        $validated = $request->validated();

        // باقي المنطق هنا
    }


    public function messages()
    {
        return [
            'client_phone.unique' => 'رقم الهاتف هذا موجود بالفعل في النظام.',
        ];
    }
}
