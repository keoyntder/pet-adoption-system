
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?> | Pet Adoption System</title>
</head>
<body>
    <h1>Welcome, <?= esc(session()->get('full_name')) ?>!</h1>
    <p>Find your new best friend.</p>

    <a href="<?= site_url('logout') ?>">Logout</a>

    <hr>

    <h2>Available Pets</h2>

    <?php if (empty($pets)): ?>
        <p>No pets are available for adoption yet.</p>
    <?php else: ?>
        <?php foreach ($pets as $pet): ?>
            <article>
                <h3><?= esc($pet['name']) ?></h3>
                <p>Species: <?= esc($pet['species']) ?></p>
                <p>Breed: <?= esc($pet['breed'] ?? 'Unknown') ?></p>
                <p>Status: <?= esc($pet['status']) ?></p>
                <a href="<?= site_url('pets/' . $pet['id']) ?>">
                    <a href="<?= site_url('my-applications') ?>">
    My Applications
</a>
    View Details and Apply
</a>
            </article>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
