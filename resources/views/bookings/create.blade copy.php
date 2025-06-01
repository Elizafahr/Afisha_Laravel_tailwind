@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2>Бронирование билетов: {{ $event->title }}</h2>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('bookings.store', $event) }}">
                            @csrf

                            <!-- Выбор типа билета -->
                            @if ($tickets->isNotEmpty())
                                <div class="mb-3">
                                    <label for="ticket_id" class="form-label">Тип билета</label>
                                    <select class="form-select" id="ticket_id" name="ticket_id">
                                        <option value="">Без типа (общий вход)</option>
                                        @foreach ($tickets as $ticket)
                                            <option value="{{ $ticket->id }}" data-price="{{ $ticket->price }}">
                                                {{ $ticket->ticket_type }} - {{ $ticket->price }} ₽
                                                (доступно: {{ $ticket->quantity_available }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if ($event->isSeated())
                                <div class="mb-6">
                                    <h3 class="text-lg font-medium mb-4">Выберите места</h3>
                                    <div class="seating-map">
                                        @foreach ($event->seats->groupBy('zone') as $zone => $seats)
                                            <div class="zone mb-8">
                                                <h4 class="text-md font-semibold mb-4">Зона {{ $zone }}</h4>

                                                <!-- Theater-style seating layout -->
                                                <div class="stage mb-4 text-center text-gray-600 font-medium">СЦЕНА</div>

                                                <div class="seating-grid">
                                                    <!-- Column headers -->
                                                    <div class="grid grid-cols-12 gap-1 mb-1">
                                                        <div class="col-header"></div>
                                                        @foreach (range(1, $seats->groupBy('number')->count()) as $col)
                                                            <div class="text-center text-xs font-medium">{{ $col }}</div>
                                                        @endforeach
                                                    </div>

                                                    <!-- Rows with seats -->
                                                    @foreach ($seats->groupBy('row') as $row => $rowSeats)
                                                        <div class="grid grid-cols-12 gap-1 mb-1">
                                                            <!-- Row label -->
                                                            <div class="row-label text-center font-medium self-center">Ряд {{ $row }}</div>

                                                            <!-- Seats -->
                                                            @foreach ($rowSeats->sortBy('number') as $seat)
                                                                <div class="seat w-8 h-8 flex items-center justify-center rounded cursor-pointer text-sm
                                                                    {{ $seat->is_reserved ? 'bg-red-500 cursor-not-allowed' : 'bg-green-200 hover:bg-green-300' }}
                                                                    {{ $seat->is_vip ? 'border-2 border-yellow-400' : '' }}"
                                                                    data-seat-id="{{ $seat->id }}"
                                                                    data-seat-number="{{ $seat->number }}"
                                                                    data-seat-row="{{ $seat->row }}"
                                                                    data-seat-zone="{{ $seat->zone }}"
                                                                    data-seat-price="{{ $seat->price }}"
                                                                    onclick="selectSeat(this)"
                                                                    title="{{ $seat->is_vip ? 'VIP место' : 'Стандартное место' }} - {{ $seat->price }}₽">
                                                                    {{ $seat->number }}
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Скрытое поле для хранения выбранных мест -->
                                <input type="hidden" id="selected_seats" name="selected_seats" value="">

                                <!-- Информация о выбранных местах -->
                                <div class="mb-3">
                                    <h5>Выбранные места:</h5>
                                    <div id="selected-seats-list" class="mb-3"></div>
                                    <p>Общее количество: <span id="selected-seats-count">0</span></p>
                                </div>
                            @else
                                <!-- Выбор места -->
                                @if ($seats->isNotEmpty())
                                    <div class="mb-3">
                                        <label for="seat_id" class="form-label">Выберите место</label>
                                        <select class="form-select" id="seat_id" name="seat_id">
                                            <option value="">Без места (общий вход)</option>
                                            @foreach ($seats as $seat)
                                                <option value="{{ $seat->id }}" data-multiplier="{{ $seat->price_multiplier }}">
                                                    {{ $seat->zone }}, ряд {{ $seat->seat_row }}, место {{ $seat->seat_number }}
                                                    (x{{ $seat->price_multiplier }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <!-- Количество билетов -->
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Количество билетов</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" max="10" value="1" required>
                                </div>
                            @endif

                            <!-- Итоговая цена -->
                            <div class="mb-3">
                                <h4>Итоговая цена: <span id="total-price">0</span> ₽</h4>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Забронировать
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const eventPrice = {{ $event->price }};
            const quantityInput = document.getElementById('quantity');
            const ticketSelect = document.getElementById('ticket_id');
            const seatSelect = document.getElementById('seat_id');
            const totalPriceElement = document.getElementById('total-price');
            const selectedSeatsList = document.getElementById('selected-seats-list');
            const selectedSeatsCount = document.getElementById('selected-seats-count');

            let selectedSeats = [];

            function calculateTotal() {
                let total = 0;

                // Если это событие с местами (isSeated)
                @if ($event->isSeated())
                    total = selectedSeats.reduce((sum, seat) => sum + seat.price, 0);
                @else
                    // Для событий без мест
                    let price = eventPrice;
                    let multiplier = 1;
                    let quantity = parseInt(quantityInput.value) || 1;

                    // Если выбран тип билета, используем его цену
                    if (ticketSelect && ticketSelect.value) {
                        const selectedOption = ticketSelect.options[ticketSelect.selectedIndex];
                        price = parseFloat(selectedOption.dataset.price);
                    }
                    // Иначе если выбрано место, применяем множитель
                    else if (seatSelect && seatSelect.value) {
                        const selectedOption = seatSelect.options[seatSelect.selectedIndex];
                        multiplier = parseFloat(selectedOption.dataset.multiplier);
                    }

                    total = price * multiplier * quantity;
                @endif

                totalPriceElement.textContent = total.toFixed(2);
            }

            function selectSeat(element) {
                // Проверяем, не забронировано ли уже место
                if (element.classList.contains('bg-red-500')) {
                    return;
                }

                const seatId = element.getAttribute('data-seat-id');
                const seatNumber = element.getAttribute('data-seat-number');
                const seatRow = element.getAttribute('data-seat-row');
                const seatZone = element.getAttribute('data-seat-zone');
                const seatPrice = parseFloat(element.getAttribute('data-seat-price'));

                const index = selectedSeats.findIndex(s => s.id === seatId);

                if (index === -1) {
                    // Добавляем место в список выбранных
                    selectedSeats.push({
                        id: seatId,
                        number: seatNumber,
                        row: seatRow,
                        zone: seatZone,
                        price: seatPrice,
                        element: element
                    });

                    // Изменяем стиль места
                    element.classList.remove('bg-green-200', 'hover:bg-green-300');
                    element.classList.add('bg-blue-500', 'text-white');
                } else {
                    // Удаляем место из списка выбранных
                    selectedSeats.splice(index, 1);

                    // Возвращаем исходный стиль
                    element.classList.remove('bg-blue-500', 'text-white');
                    element.classList.add('bg-green-200', 'hover:bg-green-300');
                }

                updateSelectedSeats();
                calculateTotal();
            }

            function updateSelectedSeats() {
                const seatsInput = document.getElementById('selected_seats');
                seatsInput.value = JSON.stringify(selectedSeats.map(seat => seat.id));

                // Обновляем список выбранных мест
                selectedSeatsList.innerHTML = '';

                if (selectedSeats.length === 0) {
                    selectedSeatsList.innerHTML = '<p>Места не выбраны</p>';
                } else {
                    const listContainer = document.createElement('div');
                    listContainer.className = 'list-group';

                    selectedSeats.forEach(seat => {
                        const seatItem = document.createElement('div');
                        seatItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                        seatItem.innerHTML = `
                            ${seat.zone}, Ряд ${seat.row}, Место ${seat.number} - ${seat.price} ₽
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                onclick="deseatSeat('${seat.id}')">
                                Убрать
                            </button>
                        `;
                        listContainer.appendChild(seatItem);
                    });

                    selectedSeatsList.appendChild(listContainer);
                }

                // Обновляем счетчик выбранных мест
                selectedSeatsCount.textContent = selectedSeats.length;

                // Обновляем количество билетов (для формы)
                if (quantityInput) {
                    quantityInput.value = selectedSeats.length;
                }
            }

            // Функция для удаления места из списка (доступна глобально)
            window.deseatSeat = function(seatId) {
                const seatIndex = selectedSeats.findIndex(s => s.id === seatId);
                if (seatIndex !== -1) {
                    const seat = selectedSeats[seatIndex];
                    seat.element.classList.remove('bg-blue-500', 'text-white');
                    seat.element.classList.add('bg-green-200', 'hover:bg-green-300');
                    selectedSeats.splice(seatIndex, 1);
                    updateSelectedSeats();
                    calculateTotal();
                }
            };

            // Инициализация расчета цены
            if (quantityInput) quantityInput.addEventListener('change', calculateTotal);
            if (ticketSelect) ticketSelect.addEventListener('change', calculateTotal);
            if (seatSelect) seatSelect.addEventListener('change', calculateTotal);

            // Инициализируем список выбранных мест
            updateSelectedSeats();
            calculateTotal();
        });
    </script>
@endsection
