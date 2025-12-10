<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $customer = Customer::where('email', $request->email)->first();

        if ($customer && Hash::check($request->password, $customer->password_hash)) {
            Auth::guard('customer')->login($customer);
            $customer->update(['last_login' => now()]);
            
            return redirect()->intended(route('home'))
                ->with('success', 'Welcome back, ' . $customer->name . '!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|min:8|confirmed',
            'phone_number' => 'nullable|string|max:15'
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'last_login' => now(),
            'address' => '',
            'city' => '',
            'province' => '',
            'postcode' => ''
        ]);

        Auth::guard('customer')->login($customer);

        return redirect()->route('home')
            ->with('success', 'Registration successful! Welcome to TokoGH.');
    }

    public function logout()
    {
        Auth::guard('customer')->logout();
        
        return redirect()->route('home')
            ->with('success', 'You have been logged out.');
    }

    public function profile()
    {
        $customer = Auth::guard('customer')->user();
        return view('auth.profile', compact('customer'));
    }

    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->ID_Customers . ',ID_Customers',
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:50',
            'province' => 'nullable|string|max:50',
            'postcode' => 'nullable|string|max:15'
        ]);

        $customer->update($request->only([
            'name', 'email', 'phone_number', 'address', 'city', 'province', 'postcode'
        ]));

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed'
        ]);

        $customer = Auth::guard('customer')->user();

        if (!Hash::check($request->current_password, $customer->password_hash)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $customer->update([
            'password_hash' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password changed successfully!');
    }
}
