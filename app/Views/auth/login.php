
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Pet Adoption System</title>
</head>
<body>
    <h1>Pet Adoption System</h1>
    <h2>Login to your account</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <p style="color: green;">
            <?= esc(session()->getFlashdata('success')) ?>
        </p>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <p style="color: red;">
            <?= esc(session()->getFlashdata('error')) ?>
        </p>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <p style="color: red;"><?= esc($error) ?></p>
        <?php endforeach; ?>
    <?php endif; ?>

    <form action="<?= site_url('login') ?>" method="post">
        <?= csrf_field() ?>

        <div>
            <label for="email">Email address</label><br>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= esc(old('email') ?? '') ?>"
                required
            >
        </div>

        <br>

        <div>
            <label for="password">Password</label><br>
            <input
                type="password"
                id="password"
                name="password"
                required
            >
        </div>

        <br>

        <button type="submit">Login</button>
    </form>

    <p>
        Don't have an account?
        <a href="<?= site_url('register') ?>">Register here</a>
    </p>
</body>
</html>
