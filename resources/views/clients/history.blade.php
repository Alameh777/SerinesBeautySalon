@extends('layouts.app')

@section('content')
<h1>{{ $client->full_name }}'s Booking History</h1>

<table>
    <thead>
        <tr>
            <th>Service</th>
            <th>Employee</th>
            <th>Start Time</th>
            <th>Payment</th>
            <th>Notes</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach($client->bookings as $booking)
            @foreach($booking->serviceEmployees as $se)
            <tr>
                <td>{{ optional($se->service)->name }}</td>
                <td>{{ optional($se->employee)->name }}</td>
                <td>{{ $booking->start_time }}</td>
                <td>{{ ucfirst($booking->payment_status) }}</td>
                <td>{{ $booking->notes }}</td>
                <td>{{ number_format($se->price, 2) }}</td>
            </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
@endsection
