<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public function saveFile(Request $request) {
        
        $request->validate([
            'file' => ['required', 'file', 'mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'file_name' => ['required', 'string']
        ]);

        $file = $request->file('file');
        $file_name = $request->get('file_name');
        $path = $file->storeAs('public', $file_name . '.' . $file->getClientOriginalExtension());
       
        $file_path = str_replace('public/', '', $path);
    
        return response()->json([
            'message' => 'File uploaded successfully', 
            'success' => true,
            'data' => asset('storage/' . $file_path),
        ]);
    }
}