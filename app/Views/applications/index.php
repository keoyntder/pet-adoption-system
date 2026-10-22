
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> | Pet Adoption</title>
</head>
<body>

    <h1>My Adoption Applications</h1>

    <p>
        Welcome, <?= esc(session()->get('full_name')) ?>!
    </p>

    <p>
        <a href="<?= site_url('pets') ?>">Browse Available Pets</a>
        |
        <a href="<?= site_url('logout') ?>">Logout</a>
    </p>

    <hr>

    <?php if (empty($applications)): ?>

        <h2>No applications yet</h2>

        <p>
            You haven't submitted an adoption application.
            Browse available pets to find your future companion.
        </p>

        <a href="<?= site_url('pets') ?>">Browse Pets</a>

    <?php else: ?>

        <p>
            Total applications: <?= count($applications) ?>
        </p>

        <?php foreach ($applications as $application): ?>

            <section style="border: 1px solid #ccc; padding: 16px; margin-bottom: 20px;">

                <?php if (! empty($application['pet_image'])): ?>
                    <img
                        src="<?= base_url('uploads/pets/' . $application['pet_image']) ?>"
                        alt="<?= esc($application['pet_name']) ?>"
                        style="width: 180px; max-width: 100%; height: auto;"
                    >
                <?php endif; ?>

                <h2><?= esc($application['pet_name']) ?></h2>

                <p>
                    <strong>Species:</strong>
                    <?= esc($application['pet_species']) ?>
                </p>

                <p>
                    <strong>Breed:</strong>
                    <?= esc($application['pet_breed'] ?: 'Not specified') ?>
                </p>

                <p>
                    <strong>Application number:</strong>
                    #<?= esc($application['id']) ?>
                </p>

                <p>
                    <strong>Date submitted:</strong>
                    <?= esc($application['application_date']) ?>
                </p>

                <p>
                    <strong>Your reason for adopting:</strong>
                </p>

                <p><?= nl2br(esc($application['reason'])) ?></p>

                <p>
                    <strong>Application status:</strong>

                    <?php if ($application['status'] === 'pending'): ?>
                        <span style="color: #b7791f;">Pending review</span>
                    <?php elseif ($application['status'] === 'approved'): ?>
                        <span style="color: green;">Approved</span>
                    <?php elseif ($application['status'] === 'rejected'): ?>
                        <span style="color: red;">Rejected</span>
                    <?php else: ?>
                        <?= esc(ucfirst($application['status'])) ?>
                    <?php endif; ?>
                </p>

                <?php if (! empty($application['admin_notes'])): ?>
                    <p><strong>Message from admin:</strong></p>
                    <p><?= nl2br(esc($application['admin_notes'])) ?></p>
                <?php endif; ?>

                <p>
                    <a href="<?= site_url('pets/' . $application['pet_id']) ?>">
                        View Pet Details
                    </a>
                </p>

            </section>

        <?php endforeach; ?>

    <?php endif; ?>

</body>
</html>
