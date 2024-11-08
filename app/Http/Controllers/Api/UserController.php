<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Board;

class UserController extends Controller
{
    /**
     * Create user.
     *
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:3|max:50',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()], 400);
        }
        if (User::query()->where('name', $request->username)->first()) {
            return response()->json(['error'=>'Пользователь с таким именем уже существует'], 409);
        }

        $user = User::create([
            'name' => $request->username,
            'email' => "$request->username@test.test",
            'password' => bcrypt(date('l jS \of F Y h:i:s A')),
        ]);

        return response()->json(["id" => $user->id, "username" => $user->name], 201);
    }

    /**
     * Add score in board by user.
     *
     * @return JsonResponse
     */
    public function score($user_id, Request $request) 
    {
        if (!User::find($user_id)) {
            return response()->json(['error'=>'Пользователь не найден'], 404);
        }

        $validator = Validator::make($request->all(), [
            'points' => 'required|integer|min:1|max:10000',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()], 400);
        }
        Board::create([
            'user_id' => $user_id,
            'score' => $request->points,
        ]);
        $total_score = Board::where('user_id', $user_id)->sum('score');
        return response()->json(["id" => $user_id, "total_score" => $total_score], 200);
    }
}
