<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                    'date.*'  => 'required',
                    'categories.*' => 'required',
                    'quantity.*' => 'nullable|integer|min:0',    //=> min là độ dài, input lấy về đang là string
                    'unit_price.*' => ['nullable','regex:/^([+-]?(\d*|\d{1,3}(,\d{3})*)(\.\d+)?\b)$/'],
                    'total_amount.*' => ['required','regex:/^([+-]?(\d*|\d{1,3}(,\d{3})*)(\.\d+)?\b)$/'],
                ];
            }
            case 'PUT':
            case 'PATCH':
            default:
                break;
        }
    }
}
