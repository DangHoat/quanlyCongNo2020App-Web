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
                    'code' => 'required|unique:clients,code',
                    'name' => 'required',
                    'telephone'=> 'present|nullable|regex:/^([0-9\s\-\+\(\)]*)$/',
                    // 'construction' => 'required'
                    // 'phone'=> ['present', 'nullable', 'regex:/^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$/']
                ];
            }
            case 'PUT':{
                return [
                    'code' => 'required',
                    'name' => 'required',
                    'telephone'=> 'present|nullable|regex:/^([0-9\s\-\+\(\)]*)$/',
                ];
            }
            case 'PATCH':
            default:
                break;
        }

    }
}
