<?php

$pageTitle = "Create Account";
require_once 'includes/header.php';

if (isLoggedIn()) {
    redirect(base_url());
}

$errors = [];
$data = [
    'first_name' => '',
    'last_name' => '',
    'username' => '',
    'email' => '',
    'phone' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    verify_csrf();

    foreach ($data as $key => $value) {
        $data[$key] = clean($_POST[$key] ?? '');
    }

    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($data['first_name'] === '') {
        $errors['first_name'] = 'First name is required.';
    }

    if ($data['username'] === '') {
        $errors['username'] = 'Username is required.';
    }

    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Enter a valid email address.';
    }

    if ($data['phone'] === '') {
        $errors['phone'] = 'Phone number is required.';
    }

    if (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters.';
    }

    if ($password !== $confirmPassword) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    if (empty($errors)) {

        $check = $conn->prepare("
            SELECT id
            FROM users
            WHERE email = ?
               OR username = ?
            LIMIT 1
        ");

        $check->execute([
            $data['email'],
            $data['username']
        ]);

        if ($check->fetch()) {

            $errors['email'] = 'Email or username already exists.';

        } else {

            $passwordHash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $insert = $conn->prepare("
                INSERT INTO users(
                    role_id,
                    first_name,
                    last_name,
                    username,
                    email,
                    phone,
                    password
                )
                VALUES(
                    5,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?
                )
            ");

            $insert->execute([
                $data['first_name'],
                $data['last_name'],
                $data['username'],
                $data['email'],
                $data['phone'],
                $passwordHash
            ]);

            setFlash(
                'success',
                'Account created successfully. Please login.'
            );

            redirect(base_url('login'));

        }

    }

}

?>

<section class="auth-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">

                <div class="auth-card">

                    <div class="text-center mb-4">

                        <img
                            src="<?= asset('images/logo.png'); ?>"
                            class="auth-logo"
                            alt="Logo">

                        <h2>Create Your Account</h2>

                        <p class="text-muted">
                            Join Frost & Brew today.
                        </p>

                    </div>

                    <form method="POST">

                        <?= csrf_field(); ?>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    First Name
                                </label>

                                <input
                                    type="text"
                                    name="first_name"
                                    class="form-control <?= isset($errors['first_name']) ? 'is-invalid' : ''; ?>"
                                    value="<?= e($data['first_name']); ?>">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Last Name
                                </label>

                                <input
                                    type="text"
                                    name="last_name"
                                    class="form-control"
                                    value="<?= e($data['last_name']); ?>">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Username
                                </label>

                                <input
                                    type="text"
                                    name="username"
                                    class="form-control"
                                    value="<?= e($data['username']); ?>">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Phone
                                </label>

                                <input
                                    type="text"
                                    name="phone"
                                    class="form-control"
                                    value="<?= e($data['phone']); ?>">

                            </div>

                            <div class="col-12 mb-3">

                                <label class="form-label">
                                    Email Address
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    value="<?= e($data['email']); ?>">

                            </div>
                                                        <div class="col-md-6 mb-3">
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
                                        class="btn btn-outline-secondary toggle-password"
                                        type="button"
                                        data-target="password">

                                        <i class="fa-solid fa-eye"></i>

                                    </button>

                                </div>

                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback d-block">
                                        <?= e($errors['password']); ?>
                                    </div>
                                <?php endif; ?>

                                <div class="progress mt-2" style="height:6px;">
                                    <div
                                        id="passwordStrength"
                                        class="progress-bar"
                                        style="width:0%">
                                    </div>
                                </div>

                                <small id="passwordText" class="text-muted">
                                    Use at least 8 characters.
                                </small>

                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Confirm Password
                                </label>

                                <div class="input-group">

                                    <input
                                        type="password"
                                        name="confirm_password"
                                        id="confirmPassword"
                                        class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>">

                                    <button
                                        class="btn btn-outline-secondary toggle-password"
                                        type="button"
                                        data-target="confirmPassword">

                                        <i class="fa-solid fa-eye"></i>

                                    </button>

                                </div>

                                <?php if (isset($errors['confirm_password'])): ?>
                                    <div class="invalid-feedback d-block">
                                        <?= e($errors['confirm_password']); ?>
                                    </div>
                                <?php endif; ?>

                            </div>

                        </div>

                        <div class="form-check mb-4">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="terms"
                                required>

                            <label
                                class="form-check-label"
                                for="terms">

                                I agree to the
                                <a href="<?= base_url('terms'); ?>">
                                    Terms & Conditions
                                </a>
                                and
                                <a href="<?= base_url('privacy-policy'); ?>">
                                    Privacy Policy
                                </a>

                            </label>

                        </div>

                        <button class="btn btn-primary w-100">
                            <i class="fa-solid fa-user-plus me-2"></i>
                            Create Account
                        </button>

                    </form>

                    <hr>

                    <p class="text-center mb-0">

                        Already have an account?

                        <a
                            href="<?= base_url('login'); ?>"
                            class="fw-semibold">

                            Login Here

                        </a>

                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

<script>

document.querySelectorAll(".toggle-password").forEach(button => {

    button.addEventListener("click", function () {

        const target = document.getElementById(this.dataset.target);
        const icon = this.querySelector("i");

        if (target.type === "password") {
            target.type = "text";
            icon.classList.replace("fa-eye","fa-eye-slash");
        } else {
            target.type = "password";
            icon.classList.replace("fa-eye-slash","fa-eye");
        }

    });

});

const password = document.getElementById("password");

if(password){

    password.addEventListener("keyup", function(){

        const value = this.value;
        const bar = document.getElementById("passwordStrength");
        const text = document.getElementById("passwordText");

        let score = 0;

        if(value.length >= 8) score++;
        if(/[A-Z]/.test(value)) score++;
        if(/[0-9]/.test(value)) score++;
        if(/[!@#$%^&*]/.test(value)) score++;

        switch(score){

            case 1:
                bar.style.width = "25%";
                bar.className = "progress-bar bg-danger";
                text.innerHTML = "Weak password";
                break;

            case 2:
                bar.style.width = "50%";
                bar.className = "progress-bar bg-warning";
                text.innerHTML = "Medium password";
                break;

            case 3:
                bar.style.width = "75%";
                bar.className = "progress-bar bg-info";
                text.innerHTML = "Good password";
                break;

            case 4:
                bar.style.width = "100%";
                bar.className = "progress-bar bg-success";
                text.innerHTML = "Strong password";
                break;

            default:
                bar.style.width = "0%";
                text.innerHTML = "Use at least 8 characters.";
        }

    });

}

</script>

<?php require_once 'includes/footer.php'; ?>