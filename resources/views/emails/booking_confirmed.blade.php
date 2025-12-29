<html>
<body>
    <p>Bonjour {{ $booking->passenger_name }},</p>
    <p>Votre réservation #{{ $booking->id }} pour le trajet {{ $booking->route->depart }} → {{ $booking->route->arrivee }} est confirmée.</p>
    <p>Vous pouvez afficher votre billet ici: <a href="{{ route('bookings.show', $booking->id) }}">Voir le billet</a></p>
    <p>Merci.</p>
</body>
</html>
