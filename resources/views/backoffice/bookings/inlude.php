//complete orders td
County,{{$booking->location['town']}} @if(isset($booking->location['center_name'])) Town ({{$booking->location['center_name']}}) @else {{ $booking->exact_location}} @endif
										@elseif(isset($booking->zone))
										 {{$booking->zone->zone_name}} ({{$booking->dropoff['dropoff_name']}})


//pending orders td


