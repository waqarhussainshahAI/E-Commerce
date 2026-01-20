<?php

namespace App\Services;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AuthAdminService
{
    public function login($req) {


         $admin = Admin::where('email', $req->email)->first();

        if (! $admin) {
            return back()->with('error', 'Email not found');
        }
        if (Auth::guard('admin')->attempt($req->validated())
        ) {
            return redirect()->route('dashboard');

        }
               return back()->withInput()->with('error', 'Invalid login credentials');


    }
    public function register($req){
        if (Admin::where('email', $req['email'])->exists()) {
            return back()->withErrors(['email' => 'This email is already registered.']);
        }

        $admin = Admin::create([
            'name' => $req['name'],
            'email' => $req['email'],
            'password' => Hash::make($req['password']),

        ]);
        Auth::guard('admin')->login($admin);

         return redirect()->route('dashboard')
            ->with('success', 'Admin registered successfully!');
    }

    public function resetPassword($req){

         $status = Password::broker('admins')->reset(
            $req->only('email', 'password', 'password_confirmation', 'token'),
            function ($admin, $password) {
                $admin->password = Hash::make($password);
                $admin->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('admin.login')
                ->with('success', 'Password reset successful! You can log in now.');
        }
                return back()->withErrors(['email' => __($status)]);

    }

    public function updatePassword($request){
        $user = auth()->user();
        if (! Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect');

            // echo '<script>console.log('.json_encode($data).');</script>';

        }
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password Updated Successfully');
    }
}
