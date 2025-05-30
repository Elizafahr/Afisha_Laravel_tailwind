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

                            <!-- Выбор места -->
                            @if ($seats->isNotEmpty())
                                <div class="mb-3">
                                    <label for="seat_id" class="form-label">Выберите место</label>
                                    <select class="form-select" id="seat_id" name="seat_id">
                                        <option value="">Без места (общий вход)</option>
                                        <!-- @foreach ($seats as $seat)
                                            <option value="{{ $seat->id }}"
                                                data-multiplier="{{ $seat->price_multiplier }}">
                                                {{ $seat->zone }}, ряд {{ $seat->seat_row }}, место
                                                {{ $seat->seat_number }}
                                                (x{{ $seat->price_multiplier }})
                                            </option>
                                        @endforeach -->
                                    </select>
                                </div>
                            @endif


                            @if($event->isSeated())
                                <div class="mb-6">
                                    <h3 class="text-lg font-medium mb-4">Выберите места</h3>
                                    <div class="seating-map">
                                        @foreach($event->seats->groupBy('zone') as $zone => $seats)
                                        <div class="zone mb-8">
                                            <h4 class="text-md font-semibold mb-2">Зона {{ $zone }}</h4>
                                            <div class="grid grid-cols-10 gap-2">
                                                @foreach($seats->groupBy('row') as $row => $rowSeats)
                                                <div class="row flex mb-2">
                                                    <div class="row-label w-8 text-center self-center">Ряд {{ $row }}</div>
                                                    @foreach($rowSeats as $seat)
                                                    <div class="seat w-8 h-8 m-1 flex items-center justify-center rounded cursor-pointer
                                                        {{ $seat->is_reserved ? 'bg-red-500 cursor-not-allowed' : 'bg-green-200 hover:bg-green-300' }}"
                                                        data-seat-id="{{ $seat->seat_id }}"
                                                        onclick="selectSeat(this, {{ $seat->seat_id }}, {{ $seat->price }})">
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
                                @endif


                            <!-- Количество билетов -->
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Количество билетов</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" min="1"
                                    max="10" value="1" required>
                            </div>

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
        // Динамический расчет цены
        document.addEventListener('DOMContentLoaded', function() {
            const eventPrice = {{ $event->price }};
            const quantityInput = document.getElementById('quantity');
            const ticketSelect = document.getElementById('ticket_id');
            const seatSelect = document.getElementById('seat_id');
            const totalPriceElement = document.getElementById('total-price');

            function calculateTotal() {
                let price = eventPrice;
                let multiplier = 1;
                let quantity = parseInt(quantityInput.value) || 1;

                // Если выбран тип билета, используем его цену
                if (ticketSelect.value) {
                    const selectedOption = ticketSelect.options[ticketSelect.selectedIndex];
                    price = parseFloat(selectedOption.dataset.price);
                }
                // Иначе если выбрано место, применяем множитель
                else if (seatSelect.value) {
                    const selectedOption = seatSelect.options[seatSelect.selectedIndex];
                    multiplier = parseFloat(selectedOption.dataset.multiplier);
                }

                const total = price * multiplier * quantity;
                totalPriceElement.textContent = total.toFixed(2);
            }

            quantityInput.addEventListener('change', calculateTotal);
            ticketSelect.addEventListener('change', calculateTotal);
            seatSelect.addEventListener('change', calculateTotal);
        });

        let selectedSeats = [];

function selectSeat(element, seatId, price) {
    if (element.classList.contains('bg-red-500')) return;
    
    const index = selectedSeats.findIndex(s => s.id === seatId);
    
    if (index === -1) {
        selectedSeats.push({ id: seatId, price: price });
        element.classList.remove('bg-green-200', 'hover:bg-green-300');
        element.classList.add('bg-blue-500', 'text-white');
    } else {
        selectedSeats.splice(index, 1);
        element.classList.remove('bg-blue-500', 'text-white');
        element.classList.add('bg-green-200', 'hover:bg-green-300');
    }
    
    updateSelectedSeats();
}

function updateSelectedSeats() {
    const seatsInput = document.getElementById('selected_seats');
    seatsInput.value = JSON.stringify(selectedSeats);
    
    const totalPrice = selectedSeats.reduce((sum, seat) => sum + seat.price, 0);
    document.getElementById('total_price').textContent = totalPrice;
}


    </script>
@endsection
