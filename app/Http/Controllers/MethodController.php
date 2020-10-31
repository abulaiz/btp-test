<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Method;

class MethodController extends Controller
{
    public function index(){
    	$data = Method::all();
    	return response()->json($data);
    }
}
