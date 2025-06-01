@extends('layouts.app')

@section('content')
    <div class="bg-gray-50">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
            <!-- Hero Section -->
            <div class="text-center mb-16">
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl md:text-6xl">
                    <span class="block">Для организаторов</span>
                    <span class="block text-red-600">мероприятий</span>
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    Все инструменты для успешной организации вашего мероприятия в одном месте
                </p>
            </div>

            @auth
                @if (!auth()->user()->hasRole('organizer'))
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
                        <div class="px-6 py-8 sm:p-10 text-center">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Вы не являетесь организатором</h3>
                            <p class="text-gray-600 mb-6">Чтобы создавать мероприятия, вам нужно получить статус организатора.
                            </p>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                    Выйти и зарегистрировать аккаунт организатора

                                </button>
                            </form>

                        </div>
                    </div>
                @endif
            @endauth

            <!-- Benefits Section -->
            <div class="mt-10 sm:mt-12">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Benefit 1 -->
                    <div class="pt-6">
                        <div class="flow-root bg-white rounded-lg px-6 pb-8 shadow-md h-full">
                            <div class="-mt-6">
                                <div>
                                    <span
                                        class="inline-flex items-center justify-center p-3 bg-red-600 rounded-md shadow-lg">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                            </path>
                                        </svg>
                                    </span>
                                </div>
                                <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">Создание мероприятий</h3>
                                <p class="mt-5 text-base text-gray-500">
                                    Легко создавайте и настраивайте страницы ваших мероприятий с полным контролем над
                                    контентом.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Benefit 2 -->
                    <div class="pt-6">
                        <div class="flow-root bg-white rounded-lg px-6 pb-8 shadow-md h-full">
                            <div class="-mt-6">
                                <div>
                                    <span
                                        class="inline-flex items-center justify-center p-3 bg-red-600 rounded-md shadow-lg">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </span>
                                </div>
                                <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">Управление расписанием
                                </h3>
                                <p class="mt-5 text-base text-gray-500">
                                    Гибкое управление расписанием, сессиями и спикерами вашего мероприятия.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Benefit 3 -->
                    <div class="pt-6">
                        <div class="flow-root bg-white rounded-lg px-6 pb-8 shadow-md h-full">
                            <div class="-mt-6">
                                <div>
                                    <span
                                        class="inline-flex items-center justify-center p-3 bg-red-600 rounded-md shadow-lg">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                            </path>
                                        </svg>
                                    </span>
                                </div>
                                <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">Регистрация участников
                                </h3>
                                <p class="mt-5 text-base text-gray-500">
                                    Полноценная система регистрации с билетами разных типов и сбором необходимой информации.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="mt-16 bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-8 sm:p-10 sm:pb-6">
                    <div class="text-center">
                        <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">
                            Готовы начать?
                        </h2>
                        <p class="mt-4 text-lg text-gray-500">
                            Создайте свое первое мероприятие уже сегодня
                        </p>
                    </div>
                    <div class="mt-8 flex justify-center">
                        @auth
                            @if (auth()->user()->hasRole('organizer'))
                                <a href="{{ route('organizer.events.create') }}"
                                    class="px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Создать мероприятие
                                </a>
                            @else
                                <a href="{{ route('organizers.apply') }}"
                                    class="px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Стать организатором
                                </a>
                            @endif
                        @else
                            <div class="space-y-4 sm:space-y-0 sm:space-x-4">
                                <a href="{{ route('register') }}"
                                    class="px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Зарегистрироваться
                                </a>
                                <a href="{{ route('login') }}"
                                    class="px-6 py-3 border border-red-600 text-base font-medium rounded-md text-red-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Войти
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="mt-16">
                <h2 class="text-2xl font-bold text-gray-900 text-center mb-8">Часто задаваемые вопросы</h2>
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="divide-y divide-gray-200">
                        <!-- FAQ Item 1 -->
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Как создать мероприятие?
                                </h3>
                            </div>
                            <div class="mt-2 text-sm text-gray-500">
                                <p>
                                    После регистрации в системе и получения статуса организатора перейдите в личный кабинет
                                    и нажмите "Создать мероприятие". Заполните необходимую информацию о вашем событии и
                                    сохраните.
                                </p>
                            </div>
                        </div>

                        <!-- FAQ Item 2 -->
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Как стать организатором?
                                </h3>
                            </div>
                            <div class="mt-2 text-sm text-gray-500">
                                <p>
                                    Зарегистрируйтесь на сайте как обычный пользователь, затем подайте заявку на статус
                                    организатора
                                    через специальную форму. После проверки ваших данных мы активируем для вас
                                    организаторские возможности.
                                </p>
                            </div>
                        </div>

                        <!-- FAQ Item 3 -->
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Как получить помощь?
                                </h3>
                            </div>
                            <div class="mt-2 text-sm text-gray-500">
                                <p>
                                    Наша служба поддержки доступна 24/7. Вы можете написать нам на
                                    <a href="mailto:support@example.com"
                                        class="text-red-600 hover:text-red-500">support@example.com</a>
                                    или воспользоваться онлайн-чатом в вашем личном кабинете.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
