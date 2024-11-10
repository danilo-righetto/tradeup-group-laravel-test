<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CepController extends Controller
{
    public function __construct() {}
    /**
     * Display a listing of the resource.
     */
    public function searchCep()
    {
        return ['name' => 'searchCep'];
    }
}
