<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Material;
use App\Department;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $materials = Material::all();

        return $materials;
    }
}
