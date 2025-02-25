<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function show(Request $request)
    {
        //load the driver field and determine if the user is a driver or not
        $user = $request->user();
        $user -> load("driver");
        return $user;

    }

    public function update(Request $request)
    {
        $request->validate([
            'year' => 'required|numeric|between:2000,2081',
            'make' => 'required',
            'model'=> 'required',
            'color'=> 'required|alpha',
            'license_plate'=>'required',
            'name'=>'required'
        ]);
        $user = $request->user();
        $user -> update($request->only('name'));

        // create or update driver associated with this user
        $user->driver()->updateOrCreate($request->only([
            'year',
            'make',
            'model',
            'color',
            'license_plate'
        ]));

        $user->load('driver');
        return $user;
    }
}
