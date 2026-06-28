<?php

$pageTitle = "Login";
require_once 'includes/header.php';

if (isLoggedIn()) {
    redirect(base_url());
}

$email = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    verify_csrf();

    $email = clean($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email)) {
        $errors['email'] = 'Email address is required.';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required.';
    }

    if (empty($errors)) {

        $stmt = $conn->prepare("
            SELECT u.*, r.name AS role_name
            FROM users u
            INNER JOIN roles r ON r.id = u.role_id
            WHERE u.email = ?
            LIMIT 1
        ");

        $stmt->execute([$email]);

        $user = $stmt->fetch();

        if (!$user) {

            $errors['login'] = 'Invalid email or password.';

        } elseif (!password_verify($password, $user['password'])) {

            $errors['login'] = 'Invalid email or password.';

        } elseif ($user['status'] !== 'Active') {

            $errors['login'] = 'Your account has been disabled.';

        } else {

            $_SESSION['user'] = [

                'id' => $user['id'],
                'role_id' => $user['role_id'],
                'role' => $user['role_name'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'name' => trim($user['first_name'] . ' ' . $user['last_name']),
                'email' => $user['email'],
                'photo' => $user['profile_photo']

            ];

            $update = $conn->prepare("
                UPDATE users
                SET last_login = NOW()
                WHERE id = ?
            ");

            $update->execute([$user['id']]);

            setFlash('success', 'Welcome back ' . $user['first_name'] . '!');

            redirect(base_url());

        }

    }

}
?>

<section class="auth-section py-5">
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-lg-5 col-md-7">

                <div class="auth-card">

                    <div class="text-center mb-4">

                        <img src="<?= asset('images/logo.png'); ?>" class="auth-logo" alt="Logo">

                        <h2 class="fw-bold mt-3">Welcome Back</h2>

                        <p class="text-muted">
                            Sign in to your Frost & Brew account.
                        </p>

                    </div>

                    <?php if (!empty($errors['login'])): ?>

                        <div class="alert alert-danger">
                            <?= e($errors['login']); ?>
                        </div>

                    <?php endif; ?>

                    <form method="POST">

                        <?= csrf_field(); ?>

                        <div class="mb-3">

                            <label class="form-label">
                                Email Address
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control <?= isset($errors['email']) ? 'is-invalid' : ''; ?>"
                                value="<?= e($email); ?>">

                            <?php if (isset($errors['email'])): ?>

                                <div class="invalid-feedback">
                                    <?= e($errors['email']); ?>
                                </div>

                            <?php endif; ?>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Password
                            </label>

                            <div class="input-group">

                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control <?= isset($errors['password']) ? 'is-invalid' : ''; ?>">

                                <button
                                    class="btn btn-outline-secondary"
                                    type="button"
                                    id="togglePassword">

                                    <i class="fa-solid fa-eye"></i>

                                </button>

                            </div>

                            <?php if (isset($errors['password'])): ?>

                                <div class="invalid-feedback d-block">
                                    <?= e($errors['password']); ?>
                                </div>

                            <?php endif; ?>

                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div class="form-check">

                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    id="remember">

                                <label
                                    class="form-check-label"
                                    for="remember">

                                    Remember Me

                                </label>

                            </div>

                            <a href="<?= base_url('forgot-password'); ?>">
                                Forgot Password?
                            </a>

                        </div>

                        <button class="btn btn-primary w-100">
                            Login
                        </button>

                    </form>

                    <hr>

                    <p class="text-center mb-0">

                        Don't have an account?

                        <a href="<?= base_url('register'); ?>" class="fw-bold">
                            Create Account
                        </a>

                    </p>

                </div>

            </div>

        </div>
    </div>
</section>

<script>
const togglePassword = document.getElementById("togglePassword");

if (togglePassword) {

    togglePassword.addEventListener("click", function () {

        const password = document.getElementById("password");

        const icon = this.querySelector("i");

        if (password.type === "password") {

            password.type = "text";

            icon.classList.remove("fa-eye");

            icon.classList.add("fa-eye-slash");

        } else {

            password.type = "password";

            icon.classList.remove("fa-eye-slash");

            icon.classList.add("fa-eye");

        }

    });

}
</script>

<?php require_once 'includes/footer.php'; ?>