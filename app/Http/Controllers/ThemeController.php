<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Theme;
class ThemeController extends Controller
{
    public function index()
    {
       // $data = Artist::all();
        $userModel = new  Theme();
        $data = [
            'posts' =>  $userModel::paginate(15),
            'pager' => $userModel->pager
        ];
        return view('Theme/themelist', $data);
    }
    public function add()
    {
       
        return view('Theme/addtheme');
    }
    public function addtheme(Request $request)
    { 
        // Validate the incoming request data
        $storeData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);
    
        // Create the artist only if validation passes
        $theme = Theme::create($storeData);
    
        // Check if artist was created successfully
        if ($theme) {
            return redirect("themelist")->with('success', 'Artist added successfully');
        } else {
            return back()->withInput()->with('error', 'Failed to add artist');
        }
    }
    public function edit($id)
{
    $theme = Theme::findOrFail($id);
    return view('Theme/edittheme', compact('theme'));
}

public function update(Request $request, $id)
{
    $theme = Theme::findOrFail($id);
echo "hello";
// exit;
    $validatedData = $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
    ]);
    $theme->update($validatedData);

    return redirect()->route('theme.list')->with('success', 'Theme updated successfully');
}

// public function update(Request $request, $id)
// {
   
//     $theme = Theme::findOrFail($id);
    
//     $validatedData = $request->validate([
//         'title' => 'required|max:255',
//         'description' => 'required',
//     ]);

//     print_r($validatedData);
//     exit;
//     $theme->update($validatedData);

//     return redirect()->route('StudioController.artistlist')->with('success', 'Artist updated successfully');
// }
public function delete($id)
{
    $artist = Theme::find($id);
    
    if ($artist) {
        $artist->delete();
        return redirect()->route('theme.list')->with('success', 'Artist deleted successfully.');
    } else {
        return redirect()->route('theme.list')->with('error', 'Failed to delete artist.');
    }
}
}
