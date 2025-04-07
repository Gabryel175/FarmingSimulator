<?php include_once 'header.php'; ?>
<div class="container mt-5">
    <h2>Logare</h2>
    <form action="login_process.php" method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Nume utilizator</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Parola</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Logare</button>
    </form>
</div>
<?php include_once 'footer.php'; ?>