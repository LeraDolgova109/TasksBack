<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ZodiacSign;

class ZodiacSignController extends Controller
{
    public function index()
    {
        return response() -> json(ZodiacSign::all());
    }

    public function show()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }
}
