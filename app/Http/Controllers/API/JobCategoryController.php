<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController as Controller;
use App\Models\JobCategory;

class JobCategoryController extends Controller
{
    public function index() {
        $categories = JobCategory::all();
        return $this->sendResponse($categories, "success");
    }
}
