
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Available Pets') ?></title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background: #f5f7f5;
            color: #24352b;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
        }

        .page-header h1 {
            margin: 0 0 8px;
        }

        .nav-links a {
            display: inline-block;
            margin: 5px 8px 5px 0;
            color: #17683b;
            text-decoration: none;
            font-weight: bold;
        }

        .pet-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 24px;
        }

        .pet-card {
            overflow: hidden;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .07);
        }

        .pet-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            display: block;
            background: #e4eae5;
        }

        .image-placeholder {
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e4eae5;
            color: #607365;
        }

        .pet-info {
            padding: 18px;
        }

        .pet-info h2 {
            margin: 0 0 12px;
        }

        .pet-info p {
            margin: 8px 0;
            line-height: 1.5;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            background: #e0f4e5;
            color: #17683b;
            font-size: 13px;
            font-weight: bold;
        }

        .view-button {
            display: block;
            margin-top: 16px;
            padding: 11px;
            border-radius: 7px;
            background: #17683b;
            color: #fff;
            text-align: center;
            text-decoration: none;
        }

        .empty-state {
            padding: 35px;
            background: white;
            border-radius: 10px;
            text-align: center;
        }

        @media (max-width: 600px) {
            body {
                padding: 16px;
            }
        }
    </style>
</head>
<body>

<div class="container">

    <header class="page-header">
        <div>
            <h1>Find Your New Best Friend</h1>
            <p>Meet the pets waiting for a loving home.</p>
        </div>

        <nav class="nav-links">
            <a href="<?= site_url('pets') ?>">Browse Pets</a>
            <a href="<?= site_url('my-applications') ?>">My Applications</a>
            <a href="<?= site_url('logout') ?>">Logout</a>
        </nav>
    </header>

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

    <?php if (empty($pets)): ?>

        <div class="empty-state">
            <h2>No pets available right now</h2>
            <p>Please check back later for pets looking for a home.</p>
        </div>

    <?php else: ?>

        <div class="pet-grid">

            <?php foreach ($pets as $pet): ?>
                <article class="pet-card">

                    <?php if (! empty($pet['image'])): ?>
                        <img
                            class="pet-image"
                            src="<?= base_url('uploads/pets/' . $pet['image']) ?>"
                            alt="<?= esc($pet['name']) ?>"
                            loading="lazy"
                        >
                    <?php else: ?>
                        <div class="image-placeholder">
                            <span>No image available</span>
                        </div>
                    <?php endif; ?>

                    <div class="pet-info">

                        <h2><?= esc($pet['name']) ?></h2>

                        <p>
                            <strong>Species:</strong>
                            <?= esc($pet['species']) ?>
                        </p>

                        <p>
                            <strong>Breed:</strong>
                            <?= esc($pet['breed'] ?: 'Unknown') ?>
                        </p>

                        <p>
                            <strong>Sex:</strong>
                            <?= esc($pet['sex']) ?>
                        </p>

                        <p>
                            <strong>Age:</strong>
                            <?= esc($pet['age'] !== null && $pet['age'] !== ''
                                ? $pet['age'] . ' year(s)'
                                : 'Unknown') ?>
                        </p>

                        <p>
                            <span class="status">
                                <?= esc(ucfirst($pet['status'])) ?>
                            </span>
                        </p>

                        <a
                            class="view-button"
                            href="<?= site_url('pets/' . $pet['id']) ?>"
                        >
                            View Details and Apply
                        </a>

                    </div>
                </article>
            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>

</body>
</html>
