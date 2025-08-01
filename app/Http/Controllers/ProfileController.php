<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Address;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    
    
    public function index()
    {
        $user = auth()->user(); // atau User::find(id) jika spesifik
        $addresses = Address::where('user_id', auth()->id())->get();
        return view('profile.index', compact('user', 'addresses'));
    }
    
    public function show($id)
    {
        $order = Order::findOrFail($id);
        $addresses = Address::where('user_id', auth()->id())->get(); // atau sesuai logikamu

        return view('order.show', compact('order', 'addresses'));
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'phone' => ['required', 'string', 'max:20'],
            'profile_picture' => ['nullable', 'image', 'max:2048'], // max 2MB
        ]);

        // Simpan foto profil jika ada
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture); // hapus lama
            }

            $path = $request->file('profile_picture')->store('profile', 'public');
            $user->profile_picture = $path;
        }

        $user->fill([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'phone' => $validated['phone'],
        ])->save();

        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
