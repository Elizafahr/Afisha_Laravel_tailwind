<?php

namespace App\Http\Controllers;

use App\Models\Organizer;
use Illuminate\Http\Request;

class OrganizerController extends Controller
{
    public function index()
    {
        $verifiedOrganizers = Organizer::withCount('events')
            ->where('is_verified', true)
            ->orderByDesc('events_count')
            ->limit(6)
            ->get();

        $allOrganizers = Organizer::withCount('events')
            ->orderByDesc('is_verified')
            ->orderByDesc('events_count')
            ->paginate(10);

        return view('organizers.index', compact('verifiedOrganizers', 'allOrganizers'));
    }

    public function show(Organizer $organizer)
    {
        $events = $organizer->events()
            ->where('start_datetime', '>', now())
            ->orderBy('start_datetime')
            ->paginate(6);

        return view('organizers.show', compact('organizer', 'events'));
    }

    public function apply(Request $request)
    {
        $validated = $request->validate([
            'organization_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'description' => 'required|string',
            'logo' => 'nullable|image|max:2048',
            'terms' => 'required|accepted',
        ]);

        // Создание заявки (можно добавить логику сохранения в базу)

        return back()->with('success', 'Ваша заявка успешно отправлена! Мы рассмотрим ее в ближайшее время.');
    }


}
