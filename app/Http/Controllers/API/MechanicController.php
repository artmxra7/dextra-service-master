<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController as Controller;

use App\Models\User;

class MechanicController extends Controller
{
    public function getAll(Request $request) {
        $mechanics = User::where('role', 'mechanic')->get();
        return $this->sendResponse($mechanics, "success");
    }

    public function getByRadius(Request $request) {
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        $mechanics = User::where('role', 'mechanic')->distance($latitude, $longitude)->get();
        return $this->sendResponse($mechanics, "success");
    }
}
