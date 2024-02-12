<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Series;
use App\Models\Theme;
use App\Models\Artist;
use App\Models\WebArtist;
use App\Models\SeasonArtist;
use App\Models\Season;
use Illuminate\Support\Facades\DB;

class SeasonController extends Controller
{

    public function index(Request $request)
    {
        // Fetch web id from session
        $webid = $request->session()->get('webid');
    
        // Retrieve the series record using the web id
        $series = Series::where('id', $webid)->first();
    
        // Retrieve the theme name
        $themeName = '';
        if ($series) {
            $themeName = $series->theme->title;
        }
    
      
        // Fetch posts with related data
        $posts = Season::with(['artists'])->where('web_id', $webid)->get();
    
        // Pass data to the view
        return view('Season.seasonlist', compact('posts', 'themeName'));
    }
    public function add()
    {
        $selectedArtistIds = session()->get('selectedArtistIds');
        $artists = Artist::pluck('name', 'id');
        
        return view('Season/addseason', compact('artists', 'selectedArtistIds'));
    }

    public function addseason(Request $request)
{
    // Validate incoming request data
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',          
        'artist_ids' => 'required|array', // Ensure artist_ids is an array
        'artist_ids.*' => 'exists:artists,id', // Ensure each artist id exists in the artists table
        // Add validation rules for other fields as needed
    ]);

    // Get the count of existing seasons and add one
   // $count = Season::count() + 1;
   $maxId = DB::table('seasons')->max('id');

   // Increment the maximum ID by one to get the new ID
   $newId = $maxId + 1;
    // Create a new web series instance
    $webSeries = new Season();
   
    $webSeries->id = $newId; // Assign the new id
    $webSeries->season_title = $validatedData['title'];
    $webSeries->description = $validatedData['description'];

    // Save the web series
    $webid = session()->get('webid');
    $name = $request->session()->get('name');
    $webSeries->web_id = $webid;
    $webSeries->created_by = $name;
    $webSeries->updated_by = $name;
    $webSeries->save();

    // Attach artists to the web series and store in the SeasonArtist table
    foreach ($validatedData['artist_ids'] as $artistId) {
        $seasonArtist = new SeasonArtist();
        $seasonArtist->season_id = $webSeries->id; // Assign the season id
        $seasonArtist->artist_id = $artistId;
        $seasonArtist->save();
    }

    return redirect("seasonlist")->with('success', 'Web series added successfully');
}



public function edit($id)
{
    // Fetch the web series by ID
    $webSeries = Season::findOrFail($id);
    
    // Retrieve all themes and artists for dropdowns
    $artists = Artist::pluck('name', 'id') ?? [];
    
    // Retrieve the IDs of artists associated with the web series
    $selectedArtistIds = SeasonArtist::where('season_id', '=', $id)->pluck('artist_id')->toArray();

    // Pass data to the edit view
    return view('Season/editseason', compact('webSeries', 'artists', 'selectedArtistIds'));
}

    public function update(Request $request, $id)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            
            'artist_ids' => 'required|array', // Ensure artist_ids is an array
            'artist_ids.*' => 'exists:artists,id', // Ensure each artist id exists in the artists table
            // Add validation rules for other fields as needed
        ]);
    
        // Find the web series to update
        $webSeries = Season::findOrFail($id);
    
        // Update web series fields
        $webSeries->season_title = $validatedData['title'];
        $webSeries->description = $validatedData['description'];
       
        $name = $request->session()->get('name');
        
        $webSeries->updated_by=$name;
        // Save the updated web series
        $webSeries->save();
    
        // Sync artists with the web series
        $webSeries->artists()->sync($validatedData['artist_ids']);
    
        return redirect("seasonlist")->with('success', 'Web series updated successfully');
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
    // Fetch the season by ID
    $season = Season::findOrFail($id);

    // Delete associated artists from the pivot table
    $season->artists()->detach();

    // Delete the season
    $season->delete();

    // Redirect with success message
    return redirect()->route('season.list')->with('success', 'Web series deleted successfully.');
}
public function updatestatus(Request $request, $id)
{
    // Find the post by ID
  
   
}



public function view(Request $request,$id)
{

    $request->session()->put('seasonid',$id);
   
    $selectedArtistIds = SeasonArtist::where('season_id', '=', $id)->pluck('artist_id')->toArray();
   session()->put('selectedArtistIds',$selectedArtistIds);
    return redirect()->route('episode.list')->with('success', 'episodes open successfully.');

}



}
