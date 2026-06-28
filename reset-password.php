<?php

$pageTitle = "Reset Password";
require_once 'includes/header.php';

if (isLoggedIn()) {
    redirect(base_url());
}

$email = clean($_GET['email'] ?? '');
$token = $_GET['token'] ?? '';

$errors = [];

if (empty($email) || empty($token)) {
    redirect(base_url('login'));
}

$stmt = $conn->prepare("
    SELECT *
    FROM password_resets
    WHERE email = ?
    ORDER BY id DESC
    LIMIT 1
");

$stmt->execute([$email]);

$reset = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reset) {
    setFlash('error', 'Invalid reset request.');
    redirect(base_url('forgot-password'));
}

if (strtotime($reset['expires_at']) < time()) {

    $delete = $conn->prepare("
        DELETE FROM password_resets
        WHERE id = ?
    ");

    $delete->execute([$reset['id']]);

    setFlash('error', 'Reset link has expired.');

    redirect(base_url('forgot-password'));
}

if (!password_verify($token, $reset['token'])) {

    setFlash('error', 'Invalid reset token.');

    redirect(base_url('forgot-password'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    verify_csrf();

    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters.';
    }

    if ($password !== $confirmPassword) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    if (empty($errors)) {

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $update = $conn->prepare("
            UPDATE users
            SET password = ?
            WHERE email = ?
        ");

        $update->execute([
            $hash,
            $email
        ]);

        $delete = $conn->prepare("
            DELETE FROM password_resets
            WHERE email = ?
        ");

        $delete->execute([$email]);

        setFlash(
            'success',
            'Password updated successfully. Please login.'
        );

        redirect(base_url('login'));
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

                        <h2>Create New Password</h2>

                        <p class="text-muted">
                            Your new password must be at least 8 characters long.
                        </p>

                    </div>

                    <form method="POST">

                        <?= csrf_field(); ?>

                        <div class="mb-3">

                            <label class="form-label">
                                New Password
                            </label>

                            <div class="input-group">

                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control <?= isset($errors['password']) ? 'is-invalid' : ''; ?>">

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary toggle-password"
                                    data-target="password">

                                    <i class="fa-solid fa-eye"></i>

                                </button>

                            </div>

                            <?php if(isset($errors['password'])): ?>

                                <div class="invalid-feedback d-block">
                                    <?= e($errors['password']); ?>
                                </div>

                            <?php endif; ?>

                        </div>

                        <div class="mb-4">

                            <label class="form-label">
                                Confirm Password
                            </label>

                            <div class="input-group">

                                <input
                                    type="password"
                                    id="confirmPassword"
                                    name="confirm_password"
                                    class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>">

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary toggle-password"
                                    data-target="confirmPassword">

                                    <i class="fa-solid fa-eye"></i>

                                </button>

                            </div>

                            <?php if(isset($errors['confirm_password'])): ?>

                                <div class="invalid-feedback d-block">
                                    <?= e($errors['confirm_password']); ?>
                                </div>

                            <?php endif; ?>

                        </div>

                        <button class="btn btn-primary w-100">
                            <i class="fa-solid fa-key me-2"></i>
                            Update Password
                        </button>

                    </form>

                </div>

            </div>

        </div>
    </div>
</section>

<script>

document.querySelectorAll(".toggle-password").forEach(button => {

    button.addEventListener("click", function(){

        const input = document.getElementById(this.dataset.target);
        const icon = this.querySelector("i");

        if(input.type === "password"){

            input.type = "text";
            icon.classList.replace("fa-eye","fa-eye-slash");

        }else{

            input.type = "password";
            icon.classList.replace("fa-eye-slash","fa-eye");

        }

    });

});

</script>

<?php require_once 'includes/footer.php'; ?>