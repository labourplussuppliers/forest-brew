<?php

$pageTitle = "Forgot Password";
require_once 'includes/header.php';

if (isLoggedIn()) {
    redirect(base_url());
}

$email = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    verify_csrf();

    $email = clean($_POST['email'] ?? '');

    if (empty($email)) {
        $errors['email'] = 'Please enter your email address.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if (empty($errors)) {

        $stmt = $conn->prepare("
            SELECT id, first_name, email
            FROM users
            WHERE email = ?
            LIMIT 1
        ");

        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {

            $errors['email'] = 'No account found with this email address.';

        } else {

            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $delete = $conn->prepare("
                DELETE FROM password_resets
                WHERE email = ?
            ");

            $delete->execute([$email]);

            $insert = $conn->prepare("
                INSERT INTO password_resets (
                    email,
                    token,
                    expires_at
                )
                VALUES (?, ?, ?)
            ");

            $insert->execute([
                $email,
                password_hash($token, PASSWORD_DEFAULT),
                $expiresAt
            ]);

            /*
            |--------------------------------------------------------------------------
            | Send Email (PHPMailer)
            |--------------------------------------------------------------------------
            |
            | Reset Link:
            |
            | https://yourdomain.com/reset-password?token=TOKEN&email=EMAIL
            |
            */

            setFlash(
                'success',
                'Password reset instructions have been sent to your email.'
            );

            redirect(base_url('login'));

        }

    }

}

?>

<section class="auth-section py-5">
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-lg-5">

                <div class="auth-card">

                    <div class="text-center mb-4">

                        <img
                            src="<?= asset('images/logo.png'); ?>"
                            class="auth-logo"
                            alt="Logo">

                        <h2>Forgot Password</h2>

                        <p class="text-muted">
                            Enter your registered email address to receive a password reset link.
                        </p>

                    </div>

                    <form method="POST">

                        <?= csrf_field(); ?>

                        <div class="mb-4">

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

                        <button class="btn btn-primary w-100">
                            <i class="fa-solid fa-paper-plane me-2"></i>
                            Send Reset Link
                        </button>

                    </form>

                    <hr>

                    <p class="text-center mb-0">

                        Remember your password?

                        <a href="<?= base_url('login'); ?>" class="fw-semibold">
                            Login
                        </a>

                    </p>

                </div>

            </div>

        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>