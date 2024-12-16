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
}
