<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function index()
    {
        $users =  DB::table('users')->paginate(15);
        $view = view('pages.users', ['users' => $users])->render();
        return $this->respondWithData($view);
    }
}