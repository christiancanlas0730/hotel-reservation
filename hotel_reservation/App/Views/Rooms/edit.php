<h2>Edit Room</h2>
<?php if(!empty($data['error'])) echo "<p style='color:red;'>".$data['error']."</p>"; ?>

<?php if(!empty($data['room'])): ?>
<form method="POST">
    Room Number: <input type="text" name="room_number" value="<?= $data['room']['room_number'] ?>" required><br>
    Type: <input type="text" name="type" value="<?= $data['room']['type'] ?>" required><br>
    Price: <input type="number" step="0.01" name="price" value="<?= $data['room']['price'] ?>" required><br>
    Status: 
    <select name="status">
        <option value="Available" <?= $data['room']['status']=='Available'?'selected':'' ?>>Available</option>
        <option value="Booked" <?= $data['room']['status']=='Booked'?'selected':'' ?>>Booked</option>
    </select><br>
    <button type="submit">Update Room</button>
</form>
<?php else: ?>
<p>Room not found.</p>
<?php endif; ?>
