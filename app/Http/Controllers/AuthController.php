<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Показывает форму регистрации
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Обрабатывает запрос регистрации пользователя
 public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'username' => ['required', 'string', 'max:50', 'unique:users'],
        'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'phone' => ['nullable', 'string', 'max:20'],
        'terms' => ['required', 'accepted']
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    User::create([
        'username' => $request->input('username'),
        'email' => $request->input('email'),
        'password_hash' => Hash::make($request->input('password')), // Исправлено с password на password_hash
        'phone' => $request->input('phone'),
        'role' => 'user',
        'is_active' => 1
    ]);

    return redirect()->route('login')->with('success', 'Вы успешно зарегистрировались!');
}

    // Метод для отображения формы входа
    protected function showLoginForm()
    {
        return view('auth.login');
    }

    // Обрабатывает запрос входа пользователя
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return redirect()->back()->withErrors(['email' => 'Неверный адрес электронной почты или пароль']);
        }

        $user = Auth::user();
        $request->session()->regenerate();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Добро пожаловать в админ-панель!');
        }
         if ($user->role === 'organizer') {
            return redirect()->route('organizer.dashboard')->with('success', 'Добро пожаловать в админ-панель!');
        }

        return redirect('/');
    }
    // Обрабатывает запрос выхода пользователя

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
