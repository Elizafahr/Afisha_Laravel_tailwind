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

                                                <div class="stage mb-4 text-center text-gray-600 font-medium">СЦЕНА</div>

                                                <div class="seating-grid">
                                                    <div class="grid grid-cols-12 gap-1 mb-1">
                                                        <div class="col-header"></div>
                                                        @foreach (range(1, $seats->groupBy('number')->count()) as $col)
                                                            <div class="text-center text-xs font-medium">{{ $col }}
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    @foreach ($seats->groupBy('row') as $row => $rowSeats)
                                                        <div class="grid grid-cols-12 gap-1 mb-1">
                                                            <div class="row-label text-center font-medium self-center">Ряд
                                                                {{ $row }}</div>

                                                            @foreach ($rowSeats->sortBy('number') as $seat)
                                                                <div class="seat w-8 h-8 flex items-center justify-center rounded cursor-pointer text-sm
                                                                    {{ $seat->is_reserved ? 'bg-red-500 cursor-not-allowed' : 'bg-green-200 hover:bg-green-300' }}
                                                                    {{ $seat->is_vip ? 'border-2 border-yellow-400' : '' }}"
                                                                    data-seat-id="{{ $seat->seat_id }}"
                                                                    onclick="selectSeat(this, {{ $seat->seat_id }}, {{ $seat->price }})"
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
                            @else
                                <!-- Выбор места -->
                                @if ($seats->isNotEmpty())
                                    <div class="mb-3">
                                        <label for="seat_id" class="form-label">Выберите место</label>
                                        <select class="form-select" id="seat_id" name="seat_id">
                                            <option value="">Без места (общий вход)</option>
                                            @foreach ($seats as $seat)
                                                <option value="{{ $seat->id }}"
                                                    data-multiplier="{{ $seat->price_multiplier }}">
                                                    {{ $seat->zone }}, ряд {{ $seat->seat_row }}, место
                                                    {{ $seat->seat_number }}
                                                    (x{{ $seat->price_multiplier }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                            @endif
                            @if ($event->isSeated())

                            <!-- Скрытое поле для хранения выбранных мест -->
                            <input type="hidden" id="selected_seats" name="selected_seats" value="">

                                <!-- Информация о выбранных местах -->
                                <div class="mb-3">
                                    <h5>Выбранные места:</h5>
                                    <div id="selected-seats-list" class="mb-3"></div>
                                    <p>Общее количество: <span id="selected-seats-count">0</span></p>
                                    <input type="hidden" id="quantity" name="quantity" value="1">
                                </div>
                            @endif
                            <!-- Итоговая цена -->
                            <div class="mb-3">
                                <h4>Итоговая цена: <span id="total-price">{{ $event->price }}</span> ₽</h4>
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

            function calculateTotal() {
                let price = eventPrice;
                let multiplier = 1;
                let quantity = parseInt(quantityInput?.value) || 1;

                // Если выбран тип билета, используем его цену
                if (ticketSelect?.value) {
                    const selectedOption = ticketSelect.options[ticketSelect.selectedIndex];
                    price = parseFloat(selectedOption.dataset.price);
                }
                // Иначе если выбрано место, применяем множитель
                else if (seatSelect?.value) {
                    const selectedOption = seatSelect.options[seatSelect.selectedIndex];
                    multiplier = parseFloat(selectedOption.dataset.multiplier);
                }

                const total = price * multiplier * quantity;
                totalPriceElement.textContent = total.toFixed(2);
            }

            quantityInput?.addEventListener('change', calculateTotal);
            ticketSelect?.addEventListener('change', calculateTotal);
            seatSelect?.addEventListener('change', calculateTotal);
        });

        let selectedSeats = [];

        function selectSeat(element, seatId, price) {
            if (element.classList.contains('bg-red-500')) return;

            const index = selectedSeats.findIndex(s => s.id === seatId);

            if (index === -1) {
                selectedSeats.push({
                    id: seatId,
                    price: price
                });
                element.classList.remove('bg-green-200', 'hover:bg-green-300');
                element.classList.add('bg-blue-500', 'text-white');
            } else {
                selectedSeats.splice(index, 1);
                element.classList.remove('bg-blue-500', 'text-white');
                element.classList.add('bg-green-200', 'hover:bg-green-300');
            }

            updateSelectedSeats();
        }

        // function updateSelectedSeats() {
        //     const seatsInput = document.getElementById('selected_seats');
        //     seatsInput.value = JSON.stringify(selectedSeats.map(seat => ({
        //         id: seat.id,
        //         price: seat.price
        //     })));



        //     seatsInput.value = JSON.stringify(selectedSeats);

        //     const totalPriceElement = document.getElementById('total-price');
        //     const selectedSeatsCount = document.getElementById('selected-seats-count');
        //     const ticketSelect = document.getElementById('ticket_id');
        //     const quantityInput = document.getElementById('quantity');

        //     const selectedSeatsList = document.getElementById('selected-seats-list');
        //     selectedSeatsList.innerHTML = selectedSeats.map(seat =>
        //         `<div class="mb-2 p-2 bg-light rounded">Место ${seat.id}  </div>`
        //     ).join('');

        //     document.getElementById('selected-seats-count').textContent = selectedSeats.length;
        //     document.getElementById('quantity').value = selectedSeats.length;

        //     calculateTotal();
        //     // Обновляем счетчик выбранных мест
        //     const seatsCount = selectedSeats.length;
        //     selectedSeatsCount.textContent = seatsCount;

        //     // Устанавливаем количество билетов равным количеству выбранных мест
        //     // Если мест не выбрано, оставляем 1 (для общих билетов)
        //     quantityInput.value = seatsCount > 0 ? seatsCount : 1;

        //     // Получаем базовую цену
        //     let basePrice = {{ $event->price }};

        //     // Если выбран тип билета, используем его цену
        //     if (ticketSelect && ticketSelect.value) {
        //         const selectedOption = ticketSelect.options[ticketSelect.selectedIndex];
        //         basePrice = parseFloat(selectedOption.dataset.price);
        //     }

        //     // Рассчитываем итоговую цену
        //     const totalPrice = basePrice * parseInt(quantityInput.value);
        //     totalPriceElement.textContent = totalPrice.toFixed(2);
        // }

        // В шаблоне бронирования
        function updateSelectedSeats() {
            const seatsInput = document.getElementById('selected_seats');
            const selectedSeatsList = document.getElementById('selected-seats-list');
            const totalPriceElement = document.getElementById('total-price');

            // Формируем массив выбранных мест
            const seatsData = selectedSeats.map(seat => ({
                id: seat.id,
                price: seat.price || {{ $event->price }}
            }));

            seatsInput.value = JSON.stringify(seatsData);

            // Обновляем отображение
            selectedSeatsList.innerHTML = selectedSeats.map(seat =>
                `<div class="mb-2 p-2 bg-light rounded">Место ${seat.id} =</div>`
            ).join('');

            // Рассчитываем общую стоимость
            const totalPrice = selectedSeats.reduce((sum, seat) => sum + (seat.price || {{ $event->price }}), 0);
            totalPriceElement.textContent = totalPrice.toFixed(2);

            // Обновляем количество
            document.getElementById('quantity').value = selectedSeats.length || 1;
        }
    </script>
@endsection
