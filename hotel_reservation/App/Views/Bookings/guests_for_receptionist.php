<h2>Bookings - <?php echo ucfirst($filter ?? 'all'); ?></h2>
<table>
    <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Room</th>
        <th>Check-in</th>
        <th>Check-out</th>
        <th>Actions</th>
    </tr>
    <?php foreach($bookings as $booking): ?>
    <tr>
        <td><?php echo $booking['id']; ?></td>
        <td><?php echo htmlspecialchars($booking['customer_name']); ?></td>
        <td><?php echo $booking['room_number']; ?></td>
        <td><?php echo $booking['check_in']; ?></td>
        <td><?php echo $booking['check_out']; ?></td>
        <td>
            <a href="/hotel_reservation/public/receptionist/checkin/<?php echo $booking['id']; ?>">Check-in</a> |
            <a href="/hotel_reservation/public/receptionist/checkout/<?php echo $booking['id']; ?>">Check-out</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

