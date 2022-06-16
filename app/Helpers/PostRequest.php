<?php

namespace App\Helpers;

use Illuminate\Http\Request;

class PostRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ];
    }
}
