<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException as ValidationValidationException;
use Nette\Schema\ValidationException;

class AuthController extends Controller
{
    /**
     * regiszrációsfelület megjelenítése
     */
    public function showRegister(){
        return view('auth.register');
    }

    /**
     * bejelentkezési felület megjelenítése
     */
    public function showLogin(){
        return view('auth.login');
    }

    
    /**
     * regisztrációs logika megvalósítása
     */
    public function register(Request $request) {

        //beviteli mezők validálása
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        //felhasználó mentése
        $user = User::create($validated);

        //sikeres regisztráció esetén bejelentkezés
        Auth::login($user);

        //bejelentkezés után átirányítás a főfeladatok listázása oldalra
        return redirect()->route('tasks.index');
    }

    /**
     * bejelentkezési logika megvalósítása
     */
    public function login(Request $request){
        //beviteli mezők validálása
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        //bejelentkezés megpróbálása
        //sikeres bejelentkezés esetén átirányitás a fő feladatok listázásának oldalára
        if(Auth::attempt($validated)){
            $request->session()->regenerate();

            return redirect()->route('tasks.index');
        }

        throw ValidationValidationException::withMessages([
            'credentials' => 'Sorry incorrect credentials'
        ]);
    }

    /**
     * kijelentkezés megvalósítása
     */
    public function logout(Request $request){
        //kijelentkezés
        Auth::logout();

        $request->session()->invalidate(); //jelenlegi munkamenet fájl törlése
        $request->session()->regenerateToken(); //csrf biztonsági token újragenerálása

        //átirányítás a bejelentkezés oldalra
        return redirect()->route('show.login');
    }
}
