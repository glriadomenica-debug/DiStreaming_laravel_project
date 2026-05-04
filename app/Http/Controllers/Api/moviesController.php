<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiMessage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Movies;
use Illuminate\Support\Facades\Validator;

class moviesController extends Controller
{
    public function index()
    {
        try {
            $queryParams = request()->all();
            $query = Movies::with('category');
            //search by title
            if (isset($queryParams['search'])) {
                $query->where('title', 'like', '%' . $queryParams['search'] . '%');
            }

            //filter by categories
            if (isset($queryParams['category_id'])) {
                $query->where('category_id', $queryParams['category_id']);
            }

            //sorting
            if (isset($queryParams['sort_by'])) {
                $order = $queryParams['sort_order'] ?? 'desc';
                $query->orderBy($queryParams['sort_by'], $order);
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $movies = $query->paginate(8);

            //Rating classification
            $movies->getCollection()->transform(function ($movie) {
                if ($movie->rating >= 8.5) {
                    $movie->rating_class = 'Top rated';
                } elseif ($movie->rating >= 7.0) {
                    $movie->rating_class = 'Popular';
                } else {
                    $movie->rating_class = 'Regular';
                }
                return $movie;
            });

            if ($movies->isEmpty()) {
                return ApiMessage::error('Error', 'No Movies found', 404);
            }

            $response = [
                'meta' => [
                    'current_page' => $movies->currentPage(),
                    'last_page' => $movies->lastPage(),
                    'per_page' => $movies->perPage(),
                    'total' => $movies->total(),
                ],
                'data' => $movies->items(),
            ];
            return ApiMessage::success('Success', $response, 200);
        } catch (\Exception $e) {
            return ApiMessage::error('Error', $e->getMessage(), 400);
        }
    }
    public function store(Request $request)
    {
        try {
            $rules = [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'rating' => 'required|numeric|min:0|max:10',
                'release_year' => 'required|integer|min:1900|max:2030',
                'thumbnail' => 'required|string',
                'video_url' => 'required|string',
                'category_id' => 'required|exists:categories,id',
            ];
            $messages = [
                'title.required' => 'Title is required',
                'title.string' => 'Title must be a string',
                'description.required' => 'Description is required',
                'description.string' => 'Description must be a string',
                'rating.required' => 'Rating is required',
                'rating.numeric' => 'Rating must be a number',
                'release_year.required' => 'Release year is required',
                'release_year.integer' => 'Invalid release year format',
                'thumbnail.required' => 'Thumbnail is required',
                'video_url' => 'Video url is required',
                // 'thumbnail.string' => 'Thumbnail must be a string',
                'category_id.required' => 'Category ID is required',
                'category_id.exists' => 'Invalid category ID',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return ApiMessage::error('Error', $validator->errors(), 400);
            }
            $movie = new Movies();
            $movie->title = $request->title;
            $movie->description = $request->description;
            $movie->rating = $request->rating;
            $movie->release_year = $request->release_year;
            $movie->thumbnail = $request->thumbnail;
            $movie->video_url = $request->video_url;
            $movie->category_id = $request->category_id;
            $movie->save();
            return ApiMessage::success('Movie successfully created', $movie, 201);
        } catch (\Throwable $th) {
            return ApiMessage::error('Error', $th->getMessage(), 400);
        }
    }

    public function show($id)
    {
        try {
            $movie = Movies::with('category')->find($id);
            if (!$movie) {
                return ApiMessage::error('Error', 'Movie not found', 404);
            }
            return ApiMessage::success('Success', $movie, 200);
        } catch (\Throwable $th) {
            return ApiMessage::error('Error', $th->getMessage(), 400);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $rules = [
                'title' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'rating' => 'sometimes|numeric|min:0|max:10',
                'release_year' => 'sometimes|integer|min:1900|max:2030',
                'thumbnail' => 'sometimes|string',
                'video_url' => 'sometimes|string',
                'category_id' => 'sometimes|exists:categories,id',
            ];
            $messages = [
                'title.string' => 'Title must be a string',
                'description.string' => 'Description must be a string',
                'rating.numeric' => 'Rating must be a number',
                'release_year.integer' => 'Invalid release year format',
                'thumbnail.string' => 'Thumbnail must be a string',
                'video_url' => 'Video url must be a string',
                'category_id.exists' => 'Invalid category ID',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return ApiMessage::error('Error', $validator->errors(), 400);
            }

            $movie = Movies::find($id);

            if (!$movie) {
                return ApiMessage::error('Error', 'Movie not found', 404);
            }
            if ($request->has('title')) {
                $movie->title = $request->title;
            }
            if ($request->has('description')) {
                $movie->description = $request->description;
            }
            if ($request->has('rating')) {
                $movie->rating = $request->rating;
            }
            if ($request->has('release_year')) {
                $movie->release_year = $request->release_year;
            }
            if ($request->has('thumbnail')) {
                $movie->thumbnail = $request->thumbnail;
            }
            if ($request->has('video_url')) {
                $movie->video_url = $request->video_url;
            }
            if ($request->has('category_id')) {
                $movie->category_id = $request->category_id;
            }
            $movie->save();

            return ApiMessage::success('Movie successfully updated', $movie, 200);
        } catch (\Throwable $th) {
            return ApiMessage::error('Error', $th->getMessage(), 400);
        }
    }

    public function destroy(Request $request, $id)
    {
        //Hard delete
        try {
            $movie = Movies::find($id);
            if (!$movie) {
                return ApiMessage::error('Error', 'Movie not found', 404);
            }
            $movie->delete();
            return ApiMessage::success('Movie successfully deleted', null, 200);
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
