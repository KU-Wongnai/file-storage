<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{

    /**
     * Save single file
     * 
     * file: file to save
     * file_name: name of file to save
     * 
     * Will return file path
     * 
     * @return json 
     */
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

    /**
     * Save multiple files
     * 
     * files: array of files
     * folder_name: folder name to save files
     * prefix: prefix for file name
     * 
     * Will return array of file paths
     * 
     * @return json
     */
    public function saveMultipleFiles(Request $request) {
        $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['required', 'file', 'mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'folder_name' => ['required', 'string'],
            'prefix' => ['string']
        ]);

        $files = $request->file('files');
        $folder_name = $request->get('folder_name');
        $prefix = $request->get('prefix') ?? '';
        $file_paths = [];

        foreach($files as $file) {
            $path = $file->storeAs('public/' . $folder_name , uniqid($prefix . '_') . '.' . $file->getClientOriginalExtension());
            $file_path = str_replace('public/', '', $path);
            array_push($file_paths, asset('storage/' . $file_path));
        }
    
        return response()->json([
            'message' => 'Files uploaded successfully', 
            'success' => true,
            'data' => $file_paths,
        ]);
    }

}