<?php
namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Support\Facades\DB;

class PlansController extends Controller
{
    public function index()
    {
        $plans =  DB::table('plans')->paginate(15);
        $view = view('pages.plans', ['plans' => $plans])->render();
        return $this->respondWithData($view);
    }
}