@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Организаторы мероприятий</h1>

    <div class="mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Проверенные организаторы</h2>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($verifiedOrganizers as $organizer)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            @if($organizer->logo_url)
                                <img src="{{ $organizer->logo_url }}" alt="{{ $organizer->organization_name }}" class="w-16 h-16 object-cover rounded-full mr-4">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 mr-4">
                                    <i class="fas fa-building text-2xl"></i>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-bold text-lg">{{ $organizer->organization_name }}</h3>
                                @if($organizer->is_verified)
                                    <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded mt-1">
                                        <i class="fas fa-check-circle mr-1"></i> Проверенный
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($organizer->description)
                            <p class="text-gray-600 mb-4 line-clamp-3">{{ $organizer->description }}</p>
                        @endif

                        <div class="text-sm text-gray-500 mb-4">
                            <div class="flex items-center mb-1">
                                <i class="fas fa-user mr-2"></i>
                                <span>{{ $organizer->contact_person }}</span>
                            </div>
                            @if($organizer->phone)
                                <div class="flex items-center mb-1">
                                    <i class="fas fa-phone mr-2"></i>
                                    <a href="tel:{{ $organizer->phone }}" class="hover:text-red-600">{{ $organizer->phone }}</a>
                                </div>
                            @endif
                            @if($organizer->email)
                                <div class="flex items-center">
                                    <i class="fas fa-envelope mr-2"></i>
                                    <a href="mailto:{{ $organizer->email }}" class="hover:text-red-600">{{ $organizer->email }}</a>
                                </div>
                            @endif
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">
                                {{ $organizer->events_count }} мероприятий
                            </span>
                            {{-- <a href="{{ route('organizers.show', $organizer) }}" class="text-red-600 hover:text-red-800 font-medium text-sm">
                                Подробнее <i class="fas fa-arrow-right ml-1"></i>
                            </a> --}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Все организаторы</h2>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Название
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Контактное лицо
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Телефон
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Мероприятий
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Статус
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($allOrganizers as $organizer)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($organizer->logo_url)
                                            <img src="{{ $organizer->logo_url }}" alt="{{ $organizer->organization_name }}" class="w-10 h-10 object-cover rounded-full mr-3">
                                        @endif
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $organizer->organization_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $organizer->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $organizer->contact_person }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $organizer->phone }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $organizer->events_count }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($organizer->is_verified)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Проверен
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            На проверке
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($allOrganizers->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $allOrganizers->links() }}
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Стать организатором</h2>
        <p class="text-gray-600 mb-6">Если вы хотите размещать свои мероприятия на нашей платформе, заполните заявку ниже.</p>

        <form action="{{ route('organizers.apply') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="org_name" class="block text-gray-700 mb-2">Название организации *</label>
                    <input
                        type="text"
                        id="org_name"
                        name="organization_name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                        required
                    >
                </div>

                <div>
                    <label for="org_contact" class="block text-gray-700 mb-2">Контактное лицо *</label>
                    <input
                        type="text"
                        id="org_contact"
                        name="contact_person"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                        required
                    >
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="org_email" class="block text-gray-700 mb-2">Электронная почта *</label>
                    <input
                        type="email"
                        id="org_email"
                        name="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                        required
                    >
                </div>

                <div>
                    <label for="org_phone" class="block text-gray-700 mb-2">Телефон *</label>
                    <input
                        type="tel"
                        id="org_phone"
                        name="phone"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                        required
                    >
                </div>
            </div>

            <div class="mb-6">
                <label for="org_description" class="block text-gray-700 mb-2">Описание деятельности *</label>
                <textarea
                    id="org_description"
                    name="description"
                    rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                    required
                ></textarea>
            </div>

            <div class="mb-6">
                <label for="org_logo" class="block text-gray-700 mb-2">Логотип организации</label>
                <input
                    type="file"
                    id="org_logo"
                    name="logo"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                    accept="image/*"
                >
            </div>

            <div class="flex items-center mb-6">
                <input
                    type="checkbox"
                    id="org_terms"
                    name="terms"
                    class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded"
                    required
                >
                <label for="org_terms" class="ml-2 block text-sm text-gray-700">
                    Я согласен с <a href="#" class="text-red-600 hover:text-red-800">условиями использования</a>
                </label>
            </div>

            <button
                type="submit"
                class="bg-red-600 text-white px-6 py-3 rounded-md font-medium hover:bg-red-700 transition"
            >
                Отправить заявку
            </button>
        </form>
    </div>
</div>
@endsection
