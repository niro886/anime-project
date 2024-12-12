<?php

namespace App\Http\Controllers\Anime;

use App\Http\Controllers\Controller;
use App\Models\Show\Show;
use App\Models\Comment\Comment;
use Auth;
use Illuminate\Http\Request;
use Redirect;



class AnimeController extends Controller
{
    public function animeDetails($id)
    {
        $show = Show::find($id);

        //you might-like section..
        $randomShows = Show::select()->orderBy("id", "desc")->take('5')->where('id', '!=', $id)->get();

        $comments = Comment::select()->orderBy('id', 'desc')->take('8')->where('show_id', $id)->get();

        return view('shows.anime-details', compact('show', 'randomShows', 'comments'));
    }


    public function insertComments(Request $request, $id)
    {

        $insertComments = Comment::create([
            "show_id" => $id,
            "user_name" => Auth::user()->name,
            "image" => Auth::user()->image,
            "comment" => $request->comment
        ]);

        if ($insertComments) {
            return Redirect::route('anime.details', $id)->with(['success' => 'Comment added Successfully!']);
        }
    }
}
