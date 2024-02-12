<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Series;
use App\Models\Theme;
use App\Models\Artist;
use App\Models\WebArtist;
use App\Models\SeasonArtist;
use App\Models\Season;
use App\Models\Episode;
use App\Models\EpisodeArtist;

use Illuminate\Support\Facades\DB;

class EpisodeController extends Controller
{

    public function index(Request $request)
    {
        $webid = $request->session()->get('webid');
        $seasonid = $request->session()->get('seasonid');
    
        // Retrieve the series record using the web id
        $series = Series::findOrFail($webid);
        
        // Retrieve the theme name
        $themeName = $series->theme->title;
    
        // Fetch all web artists associated with the web series
        $webArtists = WebArtist::where('web_id', $webid)->get();
    
        // Fetch all season artists associated with the current season
        $seasonArtists = SeasonArtist::where('season_id', $seasonid)->get();
    
        // Fetch episodes with related data
        $posts = Episode::where('season_id', $seasonid)->get();
    
        // Pass data to the view
        return view('Episode.episodelist', compact('posts', 'themeName', 'webArtists', 'seasonArtists'));
    }
    public function add()
    {
        $selectedArtistIds = session()->get('selectedArtistIds');
        $artists = Artist::pluck('name', 'id');
        
        return view('Episode/addepisode', compact('artists', 'selectedArtistIds'));
    }

    public function addepisode(Request $request)
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
   $maxId = DB::table('episodes')->max('id');

   // Increment the maximum ID by one to get the new ID
   $newId = $maxId + 1;
    // Create a new web series instance
    $webSeries = new Episode();
   
    $webSeries->id = $newId; // Assign the new id
    $webSeries->episode_title = $validatedData['title'];
    $webSeries->description = $validatedData['description'];

    // Save the web series
    $seasonid = session()->get('seasonid');
    $name = $request->session()->get('name');
    $webSeries->season_id = $seasonid;
    $webSeries->created_by = $name;
    $webSeries->updated_by = $name;
    $webSeries->save();

    // Attach artists to the web series and store in the SeasonArtist table
    foreach ($validatedData['artist_ids'] as $artistId) {
        $seasonArtist = new EpisodeArtist();
        $seasonArtist->episode_id = $webSeries->id; // Assign the season id
        $seasonArtist->artist_id = $artistId;
        $seasonArtist->save();
    }

    return redirect("episodelist")->with('success', 'Web series added successfully');
}



public function edit($id)
{
    // Fetch the episode by ID
    $episode = Episode::findOrFail($id);
   
    // Retrieve all artists for dropdown
    $artists = Artist::pluck('name', 'id') ?? [];
    
    // Retrieve the IDs of artists associated with the episode
    $selectedArtistIds = EpisodeArtist::where('episode_id', '=', $id)->pluck('artist_id')->toArray();

    // Pass data to the edit view
    return view('Episode/editepisode', compact('episode', 'artists', 'selectedArtistIds'));
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
        $webSeries = Episode::findOrFail($id);
    
        // Update web series fields
        $webSeries->episode_title = $validatedData['title'];
        $webSeries->description = $validatedData['description'];
       
        $name = $request->session()->get('name');
        
        $webSeries->updated_by=$name;
        // Save the updated web series
        $webSeries->save();
    
        // Sync artists with the web series
        $webSeries->artists()->sync($validatedData['artist_ids']);
    
        return redirect("episodelist")->with('success', 'Web series updated successfully');
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
    $episode = Episode::findOrFail($id);

    // Delete associated artists from the pivot table
    $episode->artists()->detach();

    // Delete the season
    $episode->delete();

    // Redirect with success message
    return redirect()->route('episode.list')->with('success', 'Web series deleted successfully.');
}
public function updatestatus(Request $request, $id)
{
    // Find the post by ID
  
   
}




public function view(Request $request, $id)
{
    // Retrieve web id and season id from session
    $webid = $request->session()->get('webid');
    $seasonid = $request->session()->get('seasonid');

    // Retrieve web series, season, and episode data
    $webSeries = Series::findOrFail($webid);
    $season = Season::findOrFail($seasonid);
    $episode = Episode::findOrFail($id);

    // Pass the retrieved data to the view
    return view('Episode/episodeview', compact('webSeries', 'season', 'episode'));
}

}
