@extends('layouts.admin')

@section('title', 'Создание мероприятия')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Создание мероприятия</h1>

        <form action="{{ route('admin.events.store') }}" method="POST" class="max-w-3xl">
            @csrf

            <div class="bg-white rounded-lg shadow p-6">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Название</label>
                        <input type="text" name="title" id="title" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_datetime" class="block text-sm font-medium text-gray-700">Дата начала</label>
                            <input type="datetime-local" name="start_datetime" id="start_datetime" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="end_datetime" class="block text-sm font-medium text-gray-700">Дата окончания</label>
                            <input type="datetime-local" name="end_datetime" id="end_datetime"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Место проведения</label>
                        <input type="text" name="location" id="location"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Описание</label>
                        <textarea name="description" id="description" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                </div>
                <div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Тип бронирования</label>
    <div class="mt-2 space-y-2">
        <div class="flex items-center">
            <input id="general" name="booking_type" type="radio" value="general" 
                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300"
                   {{ old('booking_type', 'general') === 'general' ? 'checked' : '' }}>
            <label for="general" class="ml-3 block text-sm font-medium text-gray-700">
                Общий вход (без мест)
            </label>
        </div>
        <div class="flex items-center">
            <input id="seated" name="booking_type" type="radio" value="seated"
                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300"
                   {{ old('booking_type') === 'seated' ? 'checked' : '' }}>
            <label for="seated" class="ml-3 block text-sm font-medium text-gray-700">
                Бронирование по местам
            </label>
        </div>
    </div>
</div>

<div id="seating-map-container" class="hidden mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">Карта мест</label>
    <div class="bg-gray-100 p-4 rounded-lg">
        <div id="seating-map" class="mx-auto" style="width: 100%; max-width: 600px;">
            <!-- Интерактивная карта будет здесь -->
        </div>
        <button type="button" id="generate-seats" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
            Сгенерировать места
        </button>
    </div>
</div>
                <div class="mb-4">
                    <label for="organizer_id" class="block text-sm font-medium text-gray-700 mb-1">Организатор *</label>
                    <select name="organizer_id" id="organizer_id" required
                        class="mt-1 block w-full rounded-md border border-gray-300 py-2 px-3 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm">
                        <option value="" disabled selected>Выберите организатора</option>
                        @forelse($organizers as $organizer)
                            <option value="{{ $organizer->organizer_id }}" >
                                {{ $organizer->organization_name }} (ID: {{ $organizer->organizer_id }})
                            </option>
                        @empty
                            <option value="" disabled>Нет доступных организаторов</option>
                        @endforelse
                    </select>
                    @error('organizer_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-6">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        Сохранить
                    </button>
                    <a href="{{ route('admin.events.index') }}" class="ml-2 text-gray-500 hover:text-gray-700">
                        Отмена
                    </a>
                </div>
            </div>
        </form>
    </div>

    
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const seatingType = document.querySelector('input[name="booking_type"]:checked');
    const seatingMapContainer = document.getElementById('seating-map-container');
    
    // Показываем/скрываем карту мест при изменении типа
    document.querySelectorAll('input[name="booking_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            seatingMapContainer.classList.toggle('hidden', this.value !== 'seated');
        });
    });
    
    // Инициализация карты мест
    const seatingMap = document.getElementById('seating-map');
    let selectedSeats = [];
    
    document.getElementById('generate-seats').addEventListener('click', function() {
        // Генерация тестовых мест (в реальном проекте можно сделать настройки)
        generateSampleSeatingMap();
    });
    
    function generateSampleSeatingMap() {
        seatingMap.innerHTML = '';
        
        // Пример генерации мест
        const zones = ['A', 'B', 'C'];
        const rowsPerZone = 5;
        const seatsPerRow = 10;
        
        zones.forEach(zone => {
            const zoneDiv = document.createElement('div');
            zoneDiv.className = 'zone mb-6';
            zoneDiv.innerHTML = `<h3 class="text-lg font-medium mb-2">Зона ${zone}</h3>`;
            
            for (let row = 1; row <= rowsPerZone; row++) {
                const rowDiv = document.createElement('div');
                rowDiv.className = 'row flex justify-center mb-2';
                
                for (let seatNum = 1; seatNum <= seatsPerRow; seatNum++) {
                    const seat = document.createElement('div');
                    seat.className = 'seat w-8 h-8 m-1 flex items-center justify-center bg-green-200 rounded cursor-pointer';
                    seat.dataset.zone = zone;
                    seat.dataset.row = row;
                    seat.dataset.number = seatNum;
                    seat.textContent = seatNum;
                    
                    seat.addEventListener('click', function() {
                        this.classList.toggle('bg-green-200');
                        this.classList.toggle('bg-blue-500');
                        this.classList.toggle('text-white');
                        
                        const seatData = {
                            zone: this.dataset.zone,
                            row: this.dataset.row,
                            number: this.dataset.number
                        };
                        
                        if (this.classList.contains('bg-blue-500')) {
                            selectedSeats.push(seatData);
                        } else {
                            selectedSeats = selectedSeats.filter(s => 
                                !(s.zone === seatData.zone && 
                                  s.row === seatData.row && 
                                  s.number === seatData.number)
                            );
                        }
                        
                        updateHiddenSeatsInput();
                    });
                    
                    rowDiv.appendChild(seat);
                }
                
                zoneDiv.appendChild(rowDiv);
            }
            
            seatingMap.appendChild(zoneDiv);
        });
    }
    
    function updateHiddenSeatsInput() {
        // Удаляем старые hidden-поля
        document.querySelectorAll('input[name="seats[]"]').forEach(el => el.remove());
        
        // Добавляем новые
        selectedSeats.forEach(seat => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'seats[]';
            input.value = `${seat.zone},${seat.row},${seat.number}`;
            seatingMap.appendChild(input);
        });
    }
    
    // Инициализация при загрузке
    if (seatingType && seatingType.value === 'seated') {
        seatingMapContainer.classList.remove('hidden');
    }
});
</script>