<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Series;
use App\Models\Theme;
use App\Models\Artist;
use App\Models\WebArtist;

class WebseriesController extends Controller
{

    public function index()
    {

      
        $userModel = Series::with(['theme', 'artists'])->where('active', 1)->paginate(15);

        $data = [
            'posts' =>  $userModel,
            'pager' => $userModel->links()
        ];
        
        return view('Web/weblist', $data);
    }


    public function add()
    {
        $themes = Theme::pluck('title', 'id');
        $artists = Artist::pluck('name', 'id');
        
        // Assuming $posts is obtained from some data retrieval logic in your controller
  
    
        return view('Web/addweb', compact('themes', 'artists'));
    }
    // public function addtheme(Request $request)
    // { 
    //     // Validate the incoming request data
    //     $storeData = $request->validate([
    //         'title' => 'required|max:255',
    //         'description' => 'required',
    //     ]);
    
    //     // Create the artist only if validation passes
    //     $theme = Theme::create($storeData);
    
    //     // Check if artist was created successfully
    //     if ($theme) {
    //         return redirect("themelist")->with('success', 'Artist added successfully');
    //     } else {
    //         return back()->withInput()->with('error', 'Failed to add artist');
    //     }
    // }


    public function addweb(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'theme_id' => 'required|exists:themes,id',
            'artist_ids' => 'required|array', // Ensure artist_ids is an array
            'artist_ids.*' => 'exists:artists,id', // Ensure each artist id exists in the artists table
            // Add validation rules for other fields as needed
        ]);
    
        // Create a new web series instance
        $webSeries = new Series();
        $webSeries->id = 'web' . str_pad(Series::count() + 1, 4, '0', STR_PAD_LEFT);
        $webSeries->title = $validatedData['title'];
        $webSeries->description = $validatedData['description'];
        $webSeries->theme_id = $validatedData['theme_id'];
        $webSeries->status = 'active'; // Set default value for status
        // $webSeries->created_by = 'shravan'; // Set default value for created_by
        $webSeries->active = 1;
        // Save the web series
       
        $name = $request->session()->get('name');
        $webSeries->created_by=$name;
        $webSeries->updated_by=$name;
        $webSeries->save();
    
        // Attach artists to the web series and store in the web-artist table
        foreach ($validatedData['artist_ids'] as $artistId) {
            $webArtist = new WebArtist();
            $webArtist->web_id = $webSeries->id;
            $webArtist->artist_id = $artistId;
            $webArtist->save();
        }
    
        return redirect("weblist")->with('success', 'Web series added successfully');
    }
    public function edit($id)
    {
        // Fetch the web series by ID
        $webSeries = Series::findOrFail($id);
        
        // Retrieve all themes and artists for dropdowns
        $themes = Theme::pluck('title', 'id') ?? [];
        $artists = Artist::pluck('name', 'id') ?? [];
        
        // Retrieve the IDs of artists associated with the web series
        $selectedArtistIds = WebArtist::where('web_id', '=', $id)->pluck('artist_id')->toArray();
    
        // Pass data to the edit view
        return view('web/editweb', compact('webSeries', 'themes', 'artists', 'selectedArtistIds'));
    }

    public function update(Request $request, $id)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'theme_id' => 'required|exists:themes,id',
            'artist_ids' => 'required|array', // Ensure artist_ids is an array
            'artist_ids.*' => 'exists:artists,id', // Ensure each artist id exists in the artists table
            // Add validation rules for other fields as needed
        ]);
    
        // Find the web series to update
        $webSeries = Series::findOrFail($id);
    
        // Update web series fields
        $webSeries->title = $validatedData['title'];
        $webSeries->description = $validatedData['description'];
        $webSeries->theme_id = $validatedData['theme_id'];
        $name = $request->session()->get('name');
        
        $webSeries->updated_by=$name;
        // Save the updated web series
        $webSeries->save();
    
        // Sync artists with the web series
        $webSeries->artists()->sync($validatedData['artist_ids']);
    
        return redirect("weblist")->with('success', 'Web series updated successfully');
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
    $webSeries = Series::findOrFail($id);
    
    
    if ($webSeries) {
        $webSeries->active = 0;
        $webSeries->save();
        return redirect()->route('web.list')->with('success', 'web series deleted successfully.');
    } else {
        return redirect()->route('web.list')->with('error', 'Failed to delete web series.');
    }
}
public function updatestatus(Request $request, $id)
{
    // Find the post by ID
    $post = Series::findOrFail($id);
    
    // Update the status based on the request data
    $post->status = $request->status; // Assuming the status is sent as 'active' or 'inactive'
    $post->save();
    
    // Return a response
    return response()->json(['success' => true, 'message' => 'Status updated successfully']);
}
public function getStatus($id)
{
    $post = Series::findOrFail($id);
    return response()->json(['status' => $post->status]);
}



public function view(Request $request,$id)
{

    $request->session()->put('webid',$id);
    $selectedArtistIds = WebArtist::where('web_id', '=', $id)->pluck('artist_id')->toArray();
   session()->put('selectedArtistIds',$selectedArtistIds);
    return redirect()->route('season.list')->with('success', 'web series seasons open successfully.');

}

}
