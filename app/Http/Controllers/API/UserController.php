<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::all();
        if ($data) {
            return ApiFormatter::createApi(200, true, $data);
        } else {
            return ApiFormatter::createApi(400, false, null);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            $data = User::where('id', '=', $user->id)->get();
            if ($data) {
                return ApiFormatter::createApi(201, true, $data);
            } else {
                return ApiFormatter::createApi(400, false, 'Data not found.');
            }
        } catch (Exception $error) {
            return ApiFormatter::createApi(400, false, $error);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = User::where('id', '=', $id)->get();
        if ($data) {
            return ApiFormatter::createApi(200, true, $data);
        } else {
            return ApiFormatter::createApi(400, false, 'Data not found.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required',
            ]);

            $user = User::findOrFail($id);
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);
            
            $data = User::where('id', '=', $user->id)->get();
            if ($data) {
                return ApiFormatter::createApi(200, true, $data);
            } else {
                return ApiFormatter::createApi(400, false, 'Data not found.');
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
            
        if ($user) {
            return ApiFormatter::createApi(200, true, "Data has been deleted.");
        } else {
            return ApiFormatter::createApi(400, false, 'Data not found.');
        }
    }
}
