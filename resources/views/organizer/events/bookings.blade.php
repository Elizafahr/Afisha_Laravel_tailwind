@extends('organizer.layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Bookings Management</h5>
    </div>
    <div class="card-body">
        @if($bookings->isEmpty())
            <div class="alert alert-info">No bookings found.</div>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Event</th>
                            <th>User</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>
                                @if($booking->event)
                                    {{ $booking->event->title }}
                                @elseif($booking->ticket && $booking->ticket->event)
                                    {{ $booking->ticket->event->title }}
                                @elseif($booking->seat && $booking->seat->event)
                                    {{ $booking->seat->event->title }}
                                @else
                                    Event deleted
                                @endif
                            </td>
                            <td>{{ $booking->user->name ?? 'Guest' }}</td>
                            <td>{{ $booking->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-primary">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
