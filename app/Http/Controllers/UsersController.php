<?php
namespace App\Http\Controllers;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\EditUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function index()
    {
        $users =  DB::table('users')->paginate(15);
        $view = view('pages.users', ['users' => $users])->render();
        return $this->respondWithData($view);
    }

    public function create(CreateUserRequest $request)
    {
        $user = new User([
            'name' => $request->user_name,
            'email' => $request->user_email,
        ]);
        if ($user->save()) {
            $view = view('parts/user', ['users' => [$user]])->render();
            return $this->respondWithData($view);
        }
        return $this->respondWithError();
    }

    public function edit(EditUserRequest $request)
    {
        $user = User::find($request->id);
        $data = [];
        if ($request->user_name) {
            $data['name'] = $request->user_name;
        }
        if ($request->user_email && $user->email !== $request->user_email) {
            if (User::whereEmail($request->user_email)->first()) {
                $this->setStatusCode(422);
                return $this->respond(['errors' => ['user_email' => ['This email has already been taken']]]);
            }
            $data['email'] = $request->user_email;
        }
        if ($data) {
            $user->update($data);
        }

        $view = view('parts.user', ['users' => [$user]])->render();
        return $this->respondWithData($view);
    }

    public function delete($id)
    {
        $user = User::whereId($id)->firstOrFail();
        $user->delete();
        return $this->respondWithSuccess('ok');
    }
}