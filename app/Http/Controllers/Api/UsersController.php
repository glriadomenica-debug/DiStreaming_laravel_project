<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiMessage;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = User::all();
            return ApiMessage::success('Success get all users data', $user, 200);
        } catch (\Throwable $th) {
            return ApiMessage::error($th->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|string|max:12',
                'password_confirmation' => 'required|string|same:password',
            ];

            $messages = [
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'email.email' => 'Email must be a valid email address',
                'password.required' => 'Password is required',
                'password.max' => 'Password must be at least 12 characters',
                'password_confirmation.required' => 'Password confirmation is required',
                'password_confirmation.same' => 'Password confirmation must be matched with password',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return ApiMessage::error($validator->errors(), 400);
            }

            try {
                //save ke tabel user 
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = bcrypt($request->password);
                $user->save();

                DB::commit();
                return ApiMessage::success('Success', 'Registration successful', 201);
            } catch (\Throwable $th) {
                DB::rollBack();
                return ApiMessage::error($th->getMessage(), 500);
            }
        } catch (\Throwable $th) {
            return ApiMessage::error($th->getMessage(), 500);
        }
    }

    public function show(string $id)
    {
        try {
            $users = User::find($id);
            if (!$users) {
                return ApiMessage::error('Error', 'User not found', 400);
            }
            return ApiMessage::success('Success', $users, 200);
        } catch (\Throwable $th) {
            return ApiMessage::error('error', $th->getMessage(), 400);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $rules = [
                'name' => 'sometimes|string|max:50',
                'email' => 'sometimes|email',
                'password' => 'sometimes|password|max:12',
                'password_confirmation' => 'sometimes|password|same:password',
            ];
            $message = [
                'name.string' => 'Name must be string',
                'email.string' => 'Email must be string',
                'email.email' => 'Email must be valid email address',
                'password.max' => 'Password must be at least 12 characters',
                'password_confirmation.same' => 'Password confirmation must be matched with password',
            ];

            $validator = Validator::make($request->all(), $rules, $message);

            if ($validator->fails()) {
                return ApiMessage::error('Error', $validator->errors(), 400);
            }

            $user = User::find($id);

            if (!$user) {
                return ApiMessage::error('Error', 'Category not found', 404);
            }
            if ($request->has('name')) {
                $user->name = $request->name;
            }
            if ($request->has('email')) {
                $user->email = $request->email;
            }
            if ($request->has('password')) {
                $user->password = $request->password;
            }
            if ($request->has('passwrod_confirmation')) {
                $user->password = $request->password;
            }
            $user->save();

            return ApiMessage::success('User successfully updated', $user, 200);
        } catch (\Throwable $th) {
            return ApiMessage::error('Error', $th->getMessage(), 400);
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return ApiMessage::error('Error', 'User not found', 404);
            }
            $user->delete();
            return ApiMessage::success('User successfully deleted', null, 200);
        } catch (\Throwable $th) {
            return ApiMessage::error('Error', $th->getMessage(), 400);
        }
    }
}
