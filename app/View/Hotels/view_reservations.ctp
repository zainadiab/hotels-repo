<h1>Reservations for Hotel <?php echo $hotel_name?></h1>

<table>
    <tr>
        <th>Room Number</th>
        <th>Reserved By</th>
        <th>Start Date</th>
        <th>End Date</th>
    </tr>
    <?php foreach ($rooms as $room): ?>
        <?php foreach ($reservations as $reservation): ?>
            <?php if ($reservation['Reservation']['room_id'] == $room['Room']['id']): ?>
                <tr>
                    <td><?php echo $room['Room']['id'] ?></td>
                    <td><?php echo $reservation['Reservation']['full_name'] ?></td>
                    <td><?php echo $reservation['Reservation']['start_date'] ?></td>
                    <td><?php echo $reservation['Reservation']['end_date'] ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
</table>
