<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;

class UserController extends Controller
{
    /**
     * The User Instance
     *
     * @var User
     */
    protected $user;

    /**
     * Create a new controller instance.
     *
     * @param User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request)
    {
        $data = $request->all();

        $data['password'] = bcrypt($data['password']);

        try {
            
            if ($request->hasFile('profile_pictura')) {
                if ($imagePath = $request->file('profile_picture')->store('profile')) {
                    $data['profile_picture'] = $imagePath;
                }
            }

            $user = $this->user->create($data);

            return response()->json([
                'error' => false,
                'message' => '',
                'data' => $user
            ], 201);

        } catch (\Throwable $th) {        
            
            return response()->json([
                'error' => true,
                'message' => 'Ocorreu um erro ao tentar criar usuário. Tente novamente.',
                'data' => []
            ], 500);
        }
    }
}
