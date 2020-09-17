<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
                    'name' => 'required',
                    // 'last_name' => 'required|min:3',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|min:6',
                    'password_confirm' => 'required|same:password',
                    'pic_file' => 'mimes:jpg,jpeg,bmp,png,gif|max:10000'
                ];
            }
            case 'PUT':{
                return [];
            }
            case 'PATCH': {
                return [
                    'name' => 'required',
                    // 'last_name' => 'required|min:3',
                    'email' => 'required|unique:users,email,' . $this->user->id,
                    'password' => 'required|min:6',
                    'password_confirm' => 'sometimes|same:password',
                    'pic_file' => 'image|mimes:jpg,jpeg,bmp,png|max:10000'
                ];
            }
            default:
                break;
        }
    }
}
