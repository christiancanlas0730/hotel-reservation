<h2>Add Customer</h2>
<?php if(!empty($data['error'])) echo "<p style='color:red;'>".$data['error']."</p>"; ?>

<form method="POST">
    Name: <input type="text" name="name" required><br>
    Email: <input type="email" name="email" required><br>
    Phone: <input type="text" name="phone" required><br>
    <button type="submit">Add Customer</button>
</form>
