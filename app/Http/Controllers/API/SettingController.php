<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController as Controller;

use App\Models\Setting;

class SettingController extends Controller
{
    public function index() {
        $settings = Setting::all();
        return $this->sendResponse($settings, "success");
    }

    public function show($id) {
        $setting = Setting::find($id);

        if (!$setting) {
            return $this->sendError('Setting not found.', 404);
        }

        return $this->sendResponse($setting, "success");
    }

    public function store(Request $request) {
        $setting = Setting::create($request->all());

        if (!$setting) {
            return $this->sendError('Setting create failed.', 404);
        }

        return $this->sendResponse($setting, "success");
    }

    public function update($id, Request $request) {
        $setting = Setting::find($id);

        if (!$setting) {
            return $this->sendError('Setting update failed.', 404);
        } else {
            $setting->update($request->all());
        }

        return $this->sendResponse($setting, "success");
    }
}
