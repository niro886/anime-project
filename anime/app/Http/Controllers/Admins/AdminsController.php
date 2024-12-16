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
}
