<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Product::all();
            return $products ;
        } catch (\Exception $e) {
            return response()->json([
                'erorr' => $e->getMessage()
            ], 400);
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $ValidateData = $request->validated();

        try {
            if (Auth::check()) {
                $product = new Product();
                $product->title = $ValidateData['title'];
                $product->description = $ValidateData['description'];
                $product->price = $ValidateData['price'];
                $product->discount = $ValidateData['discount'];
                $product->vendor_id = $ValidateData['vendor_id'];
                $product->category = $ValidateData['category'];
                $product->stock = $ValidateData['stock'];
                $product->sku = $ValidateData['sku'];
                $images = $ValidateData['images'];
                $images_array = [];
                if ($request->hasfile('images')) {
                    foreach ($images as $image) {
                        $fileName = date('YmdHis') . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                        $path = 'images/products';
                        $image->move(public_path($path), $fileName);
                        $images_array[] = url('/') . '/' . $path . '/' . $fileName;
                    }
                    $product->images = $images_array;

                    $product->save();

                    return response()->json([
                        'data' => $product,
                    ], 200);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'erorr' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        try {
            $product = Product::findOrFail($id);
            return $product ;
        } catch (\Exception $e) {
            return response()->json([
                'erorr' => $e->getMessage()
            ], 400);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'max:50|min:6',
            'description' => 'min:10',
            'price' => 'numeric',
            'category' => 'string',
            'stock' => 'numeric',
            'discount' => 'numeric',
            'sku' => 'unique:products',
            'images' => 'array',
            'images.*' => 'file|image',
        ]);
        try {
            $product =  Product::findOrFail($id);
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->discount = $request->discount;
            $product->category = $request->category;
            $product->stock = $request->stock;
            $oldimages = $product->images;
            $newimages = $request->file('images');
            $path = 'images/products';
            $images_array = [];
            if ($request->hasFile('images')) {
                foreach ($oldimages as $oldimage) {
                    $oldName = basename(parse_url($oldimage, PHP_URL_PATH));
                    $old_file_path = public_path() . '/' .  $path . '/' . $oldName;
                    if (File::exists($old_file_path)) {
                        File::delete($old_file_path);
                    }
                }
                foreach ($newimages as $newimage) {
                    $fileName = date('YmdHis') . '_' . uniqid() . '.' . $newimage->getClientOriginalExtension();
                    $newimage->move(public_path($path), $fileName);
                    $images_array[] = url('/') . '/' . $path . '/' . $fileName;
                    $product->images = $images_array;
                }
            }
            $product->save();

            return response()->json([
                'data' => $product
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
        $product = Product::findOrFail($id);
        $images = $product->images;
        foreach ($images as $image) {
            $imageName = basename(parse_url($image, PHP_URL_PATH));
            $path = 'images/products';
            $path_to_delete = public_path() . '/' . $path . '/' . $imageName;
            if (File::exists($path_to_delete)) {
                File::delete($path_to_delete);
            }
        }

        $product->delete();
    }


    public function ShowAllProductsReturnToVendor($vendorId, Request $request)
    {

        try {
            $perPage = $request->input('per_page', 10);
            $products = Product::where('vendor_id', $vendorId)->paginate($perPage);
            return response()->json([
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'erorr' => $e->getMessage()
            ], 400);
        }
    }
}
