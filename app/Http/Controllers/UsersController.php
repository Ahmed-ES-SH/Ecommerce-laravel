<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();
        return $users;
    }


    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return $user;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }



    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            // -------------------------
            //   update user data ----
            // -------------------------
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->role;

            if ($request->hasFile('image')) {
                // -------------------------
                // delete old image   ----
                // -------------------------
                $oldImage = $user->image;
                if ($oldImage) {
                    $oldImageName = basename(parse_url($oldImage, PHP_URL_PATH));
                    $pathToDelete = public_path('images/users/' . $oldImageName);
                    if (File::exists($pathToDelete)) {
                        File::delete($pathToDelete);
                    }
                }

                // -------------------------
                // update image faild   ----
                // -------------------------
                $image = $request->file('image');
                $fileName = date('YmdHis') . '.' . $image->getClientOriginalExtension();
                $path = 'images/users';
                $image->move(public_path($path), $fileName);
                $user->image = url('/') . '/' . $path . '/'  . $fileName;
            }

            $user->save();

            return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            // -------------------------
            // delete image from device ----
            // -------------------------
            $oldImage = $user->image;
            if ($oldImage) {
                $oldImageName = basename(parse_url($oldImage, PHP_URL_PATH));
                $pathToDelete = public_path('images/users/' . $oldImageName);
                if (File::exists($pathToDelete)) {
                    File::delete($pathToDelete);
                }
            }
            $user->delete();
            return response()->json([
                'messsage' => 'User deleted sucess'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'messsage' => 'User deleted faild'
            ], 400);
        }
    }

    public function current(Request $request)
    {
        // إرجاع المستخدم الحالي
        return response()->json(Auth::user());
    }
}
