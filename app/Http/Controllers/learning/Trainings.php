<?php

namespace App\Http\Controllers\learning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Trainings extends Controller
{
  public function index()
  {
    return view('content.learning.trainings'); //
  }
}
