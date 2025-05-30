<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactFormSubmitted;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contacts.index');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Отправка email
        // Mail::to('info@kamensk-events.ru')->send(new ContactFormSubmitted($validated));

        return back()->with('success', 'Ваше сообщение успешно отправлено! Мы свяжемся с вами в ближайшее время.');
    }
}
