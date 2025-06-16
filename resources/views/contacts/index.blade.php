@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Контакты</h1>

        <div class="grid md:grid-cols-2 gap-8 mb-12">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-map-marker-alt text-red-600 mr-3"></i> Наш адрес
                </h2>
                <p class="text-gray-700 mb-2">г. Каменск-Уральский</p>
                <p class="text-gray-700">ул. Ленина, 42, офис 15</p>

                <div class="mt-6">
                    <iframe
                        src="https://yandex.ru/map-widget/v1/?um=constructor%3A1234567890abcdef&amp;source=constructor"
                        width="100%"
                        height="300"
                        frameborder="0"
                        class="rounded-lg"
                    ></iframe>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-phone-alt text-red-600 mr-3"></i> Свяжитесь с нами
                </h2>

                <ul class="space-y-4">
                    <li class="flex items-start">
                        <i class="fas fa-phone text-gray-500 mt-1 mr-3"></i>
                        <div>
                            <p class="text-gray-600">Телефон</p>
                            <a href="tel:+73539301234" class="text-gray-800 font-medium hover:text-red-600">+7 (3539) 30-12-34</a>
                        </div>
                    </li>

                    <li class="flex items-start">
                        <i class="fas fa-envelope text-gray-500 mt-1 mr-3"></i>
                        <div>
                            <p class="text-gray-600">Электронная почта</p>
                            <a href="mailto:info@kamensk-events.ru" class="text-gray-800 font-medium hover:text-red-600">info@kamensk-events.ru</a>
                        </div>
                    </li>

                    {{-- <li class="flex items-start">
                        <i class="fab fa-vk text-gray-500 mt-1 mr-3"></i>
                        <div>
                            <p class="text-gray-600">Социальные сети</p>
                            <div class="flex space-x-4 mt-1">
                                <a href="#" class="text-gray-700 hover:text-red-600">
                                    <i class="fab fa-vk text-xl"></i>
                                </a>
                                <a href="#" class="text-gray-700 hover:text-red-600">
                                    <i class="fab fa-telegram text-xl"></i>
                                </a>
                                <a href="#" class="text-gray-700 hover:text-red-600">
                                    <i class="fab fa-odnoklassniki text-xl"></i>
                                </a>
                            </div>
                        </div>
                    </li> --}}
                </ul>

                <div class="mt-6">
                    <h3 class="font-bold text-gray-800 mb-2">Режим работы</h3>
                    <p class="text-gray-700">Пн-Пт: 9:00 - 18:00</p>
                    <p class="text-gray-700">Сб-Вс: 10:00 - 16:00</p>
                </div>
            </div>
        </div>

        {{-- <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Форма обратной связи</h2>

            <form action="{{ route('contacts.submit') }}" method="POST">
                @csrf

                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-gray-700 mb-2">Ваше имя</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                            required
                        >
                    </div>

                    <div>
                        <label for="email" class="block text-gray-700 mb-2">Электронная почта</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                            required
                        >
                    </div>
                </div>

                <div class="mb-6">
                    <label for="subject" class="block text-gray-700 mb-2">Тема сообщения</label>
                    <input
                        type="text"
                        id="subject"
                        name="subject"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                        required
                    >
                </div>

                <div class="mb-6">
                    <label for="message" class="block text-gray-700 mb-2">Ваше сообщение</label>
                    <textarea
                        id="message"
                        name="message"
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                        required
                    ></textarea>
                </div>

                <button
                    type="submit"
                    class="bg-red-600 text-white px-6 py-2 rounded-md font-medium hover:bg-red-700 transition"
                >
                    Отправить сообщение
                </button>
            </form>
        </div> --}}
    </div>
</div>
@endsection
