<header class="bg-white shadow-sm">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Логотип -->
            <div class="flex items-center">
                <a href="/" class="flex items-center">
                    <svg class="h-8 w-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L1 12h3v9h6v-6h4v6h6v-9h3L12 2z"/>
                    </svg>
                    <span class="ml-2 text-xl font-bold text-gray-800">Каменск-События</span>
                </a>
            </div>

          <!-- Основное меню -->
            <nav class="hidden md:flex space-x-8">
                <a href="/events" class="text-gray-700 hover:text-red-600 font-medium">Афиша</a>
                <a href="{{ route('venues.index') }}" class="text-gray-700 hover:text-red-600 font-medium">Места</a>
                <a href="{{ route('organizers.index') }}" class="text-gray-700 hover:text-red-600 font-medium">Организаторы</a>
                <a href="{{ route('news.index') }}" class="text-gray-700 hover:text-red-600 font-medium">Новости</a>
                <a href="{{ route('contacts.index') }}" class="text-gray-700 hover:text-red-600 font-medium">Контакты</a>
            </nav>

            <!-- Кнопки авторизации -->
            <div class="flex items-center space-x-4">
                <a href="/for-organizers" class="text-gray-700 hover:text-red-600 hidden md:block">
                    <i class="fas fa-user-plus mr-1"></i> Организаторам
                </a>
                @if (Auth::check())
                <a href="{{ route('profile.show', ['id' => auth()->id()]) }}">Профиль</a>


                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                     <button class="  btn" type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition">
                        <i class="fas fa-sign-in-alt mr-1">Выйти</i> </button>
                </form>
            @else
               <div class="">
                <a href="{{ route('login') }} " class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition">
                    <i class="fas fa-sign-in-alt mr-1"></i> Вход

                </a>
                {{-- <a href="{{ route('register') }} " class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition">
                    <i class="fas fa-sign-in-alt mr-1"></i> Регистрация
                </a> --}}
                </div>
            @endif

                {{--      <a href="{{ route('login') }}" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition">
                    <i class="fas fa-sign-in-alt mr-1"></i> Вход
                </a> --}}
                <button class="md:hidden text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Поисковая строка -->
        {{-- <div class="pb-4">
            <form action="{{ route('events.index') }}" method="GET" class="flex flex-col md:flex-row gap-2">
                <div class="flex-1">
                    <input type="text" name="search" placeholder="Поиск мероприятий..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
                <div class="flex gap-2">
                    <select name="category" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="">Все категории</option>
                        <option value="concert">Концерты</option>
                        <option value="theater">Театры</option>
                        <option value="sport">Спорт</option>
                    </select>
                    <input type="date" name="date" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                    <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700 transition">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div> --}}
    </div>
</header>
