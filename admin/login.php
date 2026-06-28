<?php

$pageTitle = "Admin Login";
require_once __DIR__ . '/../includes/header.php';

if (isLoggedIn()) {
    // If already logged in but not admin, logout then continue
    if (!in_array(userId(), [1])) {
        // noop
    }
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

            $adminRoles = [1,2,3,4];

            if (!in_array((int)$user['role_id'], $adminRoles, true)) {
                $errors['login'] = 'This account is not allowed to access the admin panel.';
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

                redirect(base_url('admin'));
            }

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

                        <h2 class="fw-bold mt-3">Admin Sign In</h2>

                        <p class="text-muted">Sign in to the admin dashboard.</p>

                    </div>

                    <?php if (!empty($errors['login'])): ?>

                        <div class="alert alert-danger">
                            <?= e($errors['login']); ?>
                        </div>

                    <?php endif; ?>

                    <form method="POST">

                        <?= csrf_field(); ?>

                        <div class="mb-3">

                            <label class="form-label">Email Address</label>

                            <input type="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : ''; ?>" value="<?= e($email); ?>">

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Password</label>

                            <input type="password" name="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : ''; ?>">

                        </div>

                        <div class="d-grid">
                            <button class="btn btn-primary">Sign In</button>
                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>
</section>
