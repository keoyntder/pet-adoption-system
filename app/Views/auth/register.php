
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | Pet Adoption</title>
</head>
<body>
    <h1>Create an Account</h1>

    <?php if (session()->getFlashdata('error')): ?>
        <p><?= esc(session()->getFlashdata('error')) ?></p>
    <?php endif; ?>

    <form action="<?= site_url('register') ?>" method="post">
        <?= csrf_field() ?>

        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" name="full_name"
               value="<?= old('full_name') ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email"
               value="<?= old('email') ?>" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password"
               minlength="8" required>

        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password"
               name="confirm_password" minlength="8" required>

        <button type="submit">Register</button>
    </form>

    <p>Already registered? <a href="<?= site_url('login') ?>">Log in</a></p>
</body>
</html>
