<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Category::all();
            return $categories;
        } catch (\Exception $e) {
            return response()->json([
                'erorr' => $e->getMessage()
            ]);
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            //----------------------------------
            // Validate from the request data --
            //----------------------------------

            $validator = Validator::make($request->all(), [
                'title' => 'required|max:30|min:3',
                'image' => 'required|image|file'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 400);
            }

            //----------------------------------
            // make new category --
            //----------------------------------

            $category = new Category();
            $category->title = $request->title;

            //----------------------------------
            // check for image file --
            //----------------------------------

            if ($request->hasfile('image')) {
                $image = $request->file('image');
                $fileName = date('YmdHis') . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = 'images/categories';
                $image->move(public_path($path), $fileName);
                $category->image = url('/') . '/' . $path . '/' . $fileName;
            }
            $category->save();

            return response()->json([
                'data' => $category
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {

            //----------------------------------
            // get the category by id ----------
            //----------------------------------

            $category = Category::findOrFail($id);
            return $category;
        } catch (\Exception $e) {
            return response()->json([
                'erorr' => $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        try {
            //----------------------------------
            // Validate from the request data --
            //----------------------------------
            $validator = Validator::make($request->all(), [
                'title' => 'required|max:30|min:3',
                'image' => 'required|image|file'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 400);
            }
            $category = Category::findOrFail($id);


            if ($request->has('title')) {
                $category->title = $request->title;
            };


            //----------------------------------
            // check for image faild --
            //----------------------------------

            if ($request->hasfile('image')) {
                //----------------------------------
                // delete the old image --
                //----------------------------------


                $old_file = $category->image;
                $old_name = basename(parse_url($old_file, PHP_URL_PATH));
                $path = 'images/categories';
                $path_to_delete = public_path() . '/' . $path . '/' . $old_name;

                if (File::exists($path_to_delete)) {
                    File::delete($path_to_delete);
                }

                //----------------------------------
                // upload the new image --
                //----------------------------------
                $file = $request->file('image');
                $fileName = date('YmdHis') . '.' . $file->getClientOriginalExtension();
                $file->move(public_path($path), $fileName);
                $category->image = url('/') . '/' . $path . '/' . $fileName;
            }

            $category->save();
            return response()->json([
                'data' => $category
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'erorr' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $file = $category->image;
            $name = basename(parse_url($file, PHP_URL_PATH));
            $path = 'images/categories';
            $path_to_delete = public_path() . '/' . $path . '/' . $name;

            if (File::exists($path_to_delete)) {
                File::delete($path_to_delete);
            }

            $category->delete();
        } catch (\Exception $e) {
            return response()->json([
                'erorr' => $e->getMessage()
            ]);
        }
    }
}
