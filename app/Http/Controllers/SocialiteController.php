<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }


    public function callback()
    {
        try {

            $admin = Socialite::driver('google')->user();
            $findAdmin = Admin::where('social_id', $admin->id)->first();
            if ($findAdmin) {
                Auth::guard('admin')->login($findAdmin);
                return redirect()->route('home');
            } else {
                $newAdmin = Admin::create([
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'password' => bcrypt('my-google'),
                    'social_id' => $admin->id,
                    'social_type' => 'google',
                ]);
                Auth::guard('admin')->login($newAdmin);
                return redirect()->route('home');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
