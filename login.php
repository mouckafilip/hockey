<?php
session_start();
require __DIR__ . '/config/flash.php';
require __DIR__ . '/config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['user'] ?? '');
    $password = $_POST['password'] ?? '';

    // Query database for the user
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Verify hashed password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $username;
        flash('Byli jste úspěšně přihlášeni.', 'success');
        header('Location: index.php');
        exit;
    } else {
        flash('Špatné jméno nebo heslo', 'danger');
        $error = 'Špatné jméno nebo heslo.';
    }
}

if (isset($_GET['logout'])) {
    flash('Byli jste úspěšně odhlášeni.', 'success');
}

?>

<?php 
    include __DIR__ . '/includes/header.php'; 
?>
<div class="page-body">
    <div class="login-page py-5">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <h2 class="card-title mb-4 text-center">Přihlášení</h2>
                            <form method="post" action="login.php">
                                <div class="mb-3">
                                    <label for="usern" class="form-label">Uživatelské jméno</label>
                                    <input type="text" class="form-control" id="user" name="user" placeholder="Zadejte uživatelské jméno" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Heslo</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Zadejte heslo" required>
                                        <button class="password-toggle-btn" type="button" id="togglePassword" aria-label="Zobrazit heslo">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">Přihlásit se</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('password');
        const toggleBtn = document.getElementById('togglePassword');
        const toggleIcon = toggleBtn.querySelector('i');

        toggleBtn.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            toggleIcon.classList.toggle('bi-eye');
            toggleIcon.classList.toggle('bi-eye-slash');
            toggleBtn.setAttribute('aria-label', type === 'password' ? 'Zobrazit heslo' : 'Skrýt heslo');
        });
    });
</script>
<?php 
include __DIR__ . '/includes/footer.php'; 
?>