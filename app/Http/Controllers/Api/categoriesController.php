<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiMessage;
use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class categoriesController extends Controller
{
    public function index()
    {
        try {
            $categories = Categories::withCount('movies')->get();
            if ($categories->isEmpty()) {
                return ApiMessage::error('Error', 'No categories found', 404);
            }
            return ApiMessage::success('Success', $categories, 200);
        } catch (\Exception $e) {
            return ApiMessage::error('Error', $e->getMessage(), 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|string|max:100|unique:categories,name',
                'description' => 'nullable|string',
            ];
            $messages = [
                'name.required' => 'Name is required',
                'name.string' => 'Name must be a string',
                'name.max' => 'Name must not exceed 100 characters',
                'name.unique' => 'Category name already exists',
                'description.string' => 'Description must be a string',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return ApiMessage::error('Error', $validator->errors(), 400);
            }
            $category = new Categories();
            $category->name = $request->name;
            $category->description = $request->description;
            $category->save();
            return ApiMessage::success('Category successfully created', $category, 201);
        } catch (\Throwable $th) {
            return ApiMessage::error('Error', $th->getMessage(), 400);
        }
    }

    public function show($id)
    {
        try {
            $category = Categories::find($id);
            if (!$category) {
                return ApiMessage::error('Error', 'Category not found', 404);
            }
            return ApiMessage::success('Success', $category, 200);
        } catch (\Throwable $th) {
            return ApiMessage::error('Error', $th->getMessage(), 400);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $rules = [
                'name' => 'sometimes|string|max:100|unique:categories,name,' . $id,
                'description' => 'sometimes|string',
            ];
            $messages = [
                'name.string' => 'Name must be a string',
                'description.string' => 'description must be a string',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return ApiMessage::error('Error', $validator->errors(), 400);
            }

            $category = Categories::find($id);

            if (!$category) {
                return ApiMessage::error('Error', 'Category not found', 404);
            }
            if ($request->has('name')) {
                $category->name = $request->name;
            }
            if ($request->has('description')) {
                $category->description = $request->description;
            }
            $category->save();

            return ApiMessage::success('Category successfully updated', $category, 200);
        } catch (\Throwable $th) {
            return ApiMessage::error('Error', $th->getMessage(), 400);
        }
    }

    public function destroy(Request $request, $id)
    {
        //Hard delete
        try {
            $category = Categories::find($id);
            if (!$category) {
                return ApiMessage::error('Error', 'Category not found', 404);
            }
            $count = $category->movies()->count();
            if ($count > 0) {
                return ApiMessage::error(
                    'Error',
                    "Category is used by $count movie(s). Cannot delete.",
                    400
                );
            }
            $category->delete();
            return ApiMessage::success('success', 'Category successfully deleted', 200);
        } catch (\Throwable $th) {
            return ApiMessage::error('Error', $th->getMessage(), 400);
        }

        //Soft delete
        // try {
        //     $category = Categories::where('id', $id)->where('is_deleted', false)->first();

        //     if (!$category) {
        //         return ApiMessage::error('Error', 'Category not found', 404);
        //     }
        //     $category->is_deleted = true;
        //     $category->save();
        //     return ApiMessage::success('Category successfully deleted', null, 200);
        // } catch (\Throwable $th) {
        //     return ApiMessage::error('Error', $th->getMessage(), 400);
        // }
    }
}
