<?php 
include_once 'header.php';
include_once 'register_process.php'; 
?>

<form method="POST" action="" class="mt-3">
    <label for="username">Username:</label>
    <input type="text" name="username" required>

    <label for="email">Email:</label>
    <input type="email" name="email" required>

    <label for="password">Password:</label>
    <input type="password" name="password" required>

    <button type="submit">Register</button>
</form>
</body>
</html>
