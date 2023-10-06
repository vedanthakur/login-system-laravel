<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;

class ImageController extends Controller
{
    public function index(){
        $images = Image::all();
        return view('images', ['images' => $images]);
    }

    public function createForm()
    {
        return view('image-upload');
    }

    public function fileUpload(Request $request)
    {
        $request->validate([
            'imageFile' => 'required',
            'imageFile.*' => 'mimes:jpeg,jpg,png|max:2048'
        ]);

        if ($request->hasfile('imageFile')) {
            $imageNames = '';
            foreach ($request->file('imageFile') as $file) {
                $name = $file->getClientOriginalName();
                $file->move(public_path() . '/uploads/', $name);
                $imageNames .= time() . '.'.$name . ',';
            }

            // Remove the last comma.
            $imageNames = substr($imageNames, 0, -1);

            $fileModel = new Image();
            $fileModel->name = $imageNames;
            $fileModel->image_path = $imageNames;
            $fileModel->save();

            return back()->with('success', 'File uploaded successfully!!');
        }
    }

    public function destroy(Image $image){
        $photos = explode(',', $image->image_path);
        foreach ($photos as $photo) {
            $image_name = public_path('uploads').'/'.$image->image_path;
            unlink($image_name);
        }
        $image->delete();
    }
}
