<?php

namespace App\Http\Controllers;

use App\Models\vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    public function index()
    {
        try {
            $vendors = vendor::all();
            return $vendors;
        } catch (\Exception $e) {
            return response()->json([
                'erorr' => $e->getMessage()
            ], 400);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_name' => 'required|max:30|min:6',
            'store_name' => 'required|max:30|min:6',
            'storedescription' => 'required|max:255|min:10',
            'vendor_email' => 'required|email|unique:vendors',
            'vendor_phone' => 'required|unique:vendors,vendor_phone',
            'storeurl' => 'nullable',
            'adress' => 'string|nullable',
            'category' => 'required',
            'image' => 'image|file|nullable' //
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $vendor = new vendor();
            $vendor->vendor_name = $request->vendor_name;
            $vendor->store_name = $request->store_name;
            $vendor->vendor_email = $request->vendor_email;
            $vendor->vendor_phone = $request->vendor_phone;
            $vendor->storeurl = $request->storeurl;
            $vendor->adress = $request->adress;
            $vendor->storedescription = $request->storedescription;
            $vendor->category = $request->category;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = date('YmdHis') . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = 'images/vendors';
                $image->move(public_path($path), $fileName);
                $vendor->logo = url('/') . '/' . $path . '/' . $fileName;
            }
            $vendor->save();

            return response()->json([
                'data' => $vendor
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'erorrs' => $e->getMessage()
            ], 400);
        }
    }

    public function show($id)
    {
        try {
            $vendor = vendor::findOrFail($id);
            return $vendor;
        } catch (\Exception $e) {
            return response()->json([
                'erorr' => $e->getMessage()
            ], 400);
        }
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'vendor_name' => 'max:30|min:6',
            'store_name' => 'max:30|min:6',
            'storedescription' => 'max:255|min:10',
            'vendor_email' => 'email',
            'vendor_phone' => 'numeric',
            'storeurl' => 'nullable',
            'adress' => 'string|nullable',
            'category' => 'required',
            'logo' => 'file|image|nullable'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $fields = [
            'vendor_name', 
            'store_name', 
            'vendor_email', 
            'vendor_phone', 
            'storeurl', 
            'adress', 
            'storedescription', 
            'category'
        ];

        try {
            $vendor = Vendor::findOrFail($id);

            foreach ($fields as $field) {
                if ($request->has($field)) {
                    $vendor->$field = $request->$field;
                }
            }

            $oldlogo = $vendor->logo;
            if ($request->hasFile('logo')) {

                $oldname = basename(parse_url($oldlogo, PHP_URL_PATH));
                $path = 'images/vendors';
                $path_to_delete = public_path() . '/' . $path . '/' . $oldname;

                if (File::exists($path_to_delete)) {
                    File::delete($path_to_delete);
                }
                $logo = $request->file('logo');
                $logoName = date('YmdHis') . '.' . $logo->getClientOriginalExtension();

                $logo->move(public_path($path), $logoName);
                $vendor->logo = url('/') . '/' . $path . '/' . $logoName;
            }

            $vendor->save();
            return response()->json([
                'data' => $vendor
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $vendor = vendor::findOrFail($id);
            $file = $vendor->logo;
            $name = basename(parse_url($file, PHP_URL_PATH));
            $path = 'images/vendors';
            $path_to_delete = public_path() . '/' . $path . '/' . $name;

            if (File::exists($path_to_delete)) {
                File::delete($path_to_delete);
            }

            $vendor->delete();
        } catch (\Exception $e) {
            return response()->json([
                'erorr' => $e->getMessage()
            ]);
        }
    }
}
