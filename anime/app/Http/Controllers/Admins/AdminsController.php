<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Admin\Admin;
use App\Models\Show\Show;
use App\Models\Episode\Episode;
use Redirect;
use File;
use Auth;


class AdminsController extends Controller
{
    public function viewLogin()
    {
        return view('admins.login');
    }


    public function checkLogin(Request $request)
    {
        $remember_me = $request->has('remember_me') ? true : false;

        if (auth()->guard('admin')->attempt(['email' => $request->input("email"), 'password' => $request->input("password")], $remember_me)) {

            return redirect()->route('admins.dashboard');
        }

        return redirect()->back()->with(['error' => 'error logging in']);
    }



    public function index()
    {
        $shows = Show::select()->count();
        $episodes = Episode::select()->count();
        $admins = Admin::select()->count();
        $categories = Category::select()->count();

        return view('admins.index', compact('shows', 'episodes', 'admins', 'categories'));
    }


    public function allAdmins()
    {
        $allAdmins = Admin::select()->orderBy('id', 'desc')->get();

        return view('admins.allAdmins', compact('allAdmins'));
    }



    public function createAdmins()
    {
        return view('admins.createadmins');
    }


    public function storeAdmins(Request $request)
    {
        $storeAdmins = Admin::create([
            "email" => $request->email,
            "name" => $request->name,
            "password" => Hash::make($request->password),
        ]);

        if ($storeAdmins) {
            return Redirect::route('admins.all')->with(['success' => "Admin created Successfully !"]);
        }

    }


    public function allShows()
    {
        $allShows = Show::select()->orderBy('id', 'desc')->get();

        return view('admins.allshows', compact('allShows'));
    }


    public function createShows()
    {
        $categories = Category::all();

        return view('admins.createshows', compact('categories'));
    }


    public function storeShows(Request $request)
    {

        Request()->validate([
            "name" => "required|max:40",
            "image" => "required|max:600",
            "description" => "required",
            "type" => "required|max:40",
            "date_aired" => "required|max:40",
            "studios" => "required|max:40",
            "status" => "required|max:40",
            "genere" => "required|max:40",
            "duration" => "required|max:40",
            "quality" => "required|max:40",
        ]);

        $destinationPath = 'assets/img/';
        $myimage = $request->image->getClientOriginalName();
        $request->image->move(public_path($destinationPath), $myimage);

        $storeShows = Show::create([
            "name" => $request->name,
            "image" => $myimage,
            "description" => $request->description,
            "type" => $request->type,
            "date_aired" => $request->date_aired,
            "studios" => $request->studios,
            "status" => $request->status,
            "genere" => $request->genere,
            "duration" => $request->duration,
            "quality" => $request->quality,
        ]);

        if ($storeShows) {
            return Redirect::route('shows.all')->with(['success' => "Show created Successfully !"]);
        }

    }


    public function deleteShows($id)
    {
        $show = Show::find($id);

        if (File::exists(public_path('assets/img/' . $show->image))) {
            File::delete(public_path('assets/img/' . $show->image));
        } else {
            //dd('File does not exists.');
        }

        $show->delete();

        if ($show) {
            return Redirect::route('shows.all')->with(['delete' => "Show deleted Successfully !"]);
        }

    }


    public function allGenres()
    {
        $allGenres = Category::select()->orderBy('id', 'desc')->get();

        return view('admins.allgenres', compact('allGenres'));
    }


    public function deleteGenres($id)
    {
        $genre = Category::find($id);


        $genre->delete();

        if ($genre) {
            return Redirect::route('genres.all')->with(['delete' => "Genre deleted Successfully !"]);
        }

    }


    public function createGenres()
    {
        return view('admins.creategenres');
    }




    public function storeGenres(Request $request)
    {

        Request()->validate([
            "name" => "required|max:40",
        ]);


        $storeGenres = Category::create([
            "name" => $request->name,
        ]);

        if ($storeGenres) {
            return Redirect::route('genres.all')->with(['success' => "Genre created Successfully !"]);
        }

    }



    public function allEpisodes()
    {
        $allEpisodes = Episode::select()->orderBy('id', 'desc')->get();

        return view('admins.allepisodes', compact('allEpisodes'));
    }


    public function createEpisodes()
    {
        $shows = Show::all();

        return view('admins.createepisodes', compact('shows'));
    }

    public function storeEpisodes(Request $request)
    {

        Request()->validate([
            "episode_name" => "required|max:40",
            "thumbnail" => "required|max:600",
            "video" => "required",
            "show_id" => "required|max:40",
        ]);

        $destinationPath = 'assets/thumbnails/';
        $myimage = $request->image->getClientOriginalName();
        $request->image->move(public_path($destinationPath), $myimage);

        $destinationPathVideo = 'assets/videos/';
        $myvideo = $request->video->getClientOriginalName();
        $request->video->move(public_path($destinationPathVideo), $myvideo);

        $storeEpisodes = Episode::create([
            "episode_name" => $request->name,
            "thumbnail" => $myimage,
            "video" => $myvideo,
            "show_id" => $request->show_id,

        ]);

        if ($storeEpisodes) {
            return Redirect::route('episodes.all')->with(['success' => "Episode created Successfully !"]);
        }

    }



    public function deleteEpisodes($id)
    {
        $episode = Episode::find($id);

        if (File::exists(public_path('assets/videos/' . $episode->video))) {
            File::delete(public_path('assets/videos/' . $episode->video));
        } else {
            //dd('File does not exists.');
        }

        $episode->delete();

        if ($episode) {
            return Redirect::route('episodes.all')->with(['delete' => "Episode deleted Successfully !"]);
        }

    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('view.login');
    }




}
