<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\Organizer;
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
        // Дополнительные правила валидации для организатора
        if ($request->is_organizer) {
            $validator->addRules([
                'organization_name' => ['required', 'string', 'max:255'],
                'contact_person' => ['required', 'string', 'max:255'],
                'contact_info' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048']
            ]);
        }
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // Создаем пользователя
        $user = User::create([
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password_hash' => Hash::make($request->input('password')),
            'phone' => $request->input('phone'),
            'role' => $request->is_organizer ? 'organizer' : 'user',
            'is_active' => 1
        ]);

        // Если это организатор, создаем запись в таблице организаторов
        if ($request->is_organizer) {
            $organizerData = [
                'user_id' => $user->user_id,
                'organization_name' => $request->organization_name,
                'description' => $request->description ?? '',
                'contact_person' => $request->contact_person,
                'contact_info' => $request->contact_info,
                'is_verified' => false, // По умолчанию не верифицирован
            ];
            // Обработка логотипа
            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('organizers/logos', 'public');
                $organizerData['logo_url'] = $path;
            }
            Organizer::create($organizerData);
        }
        Auth::login($user);
        // Редирект в зависимости от роли
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if ($user->role === 'organizer') {
            return redirect()->route('organizer.dashboard');
        }
        return redirect('/');
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
