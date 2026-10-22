
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
</head>
<body>

<h1><?= esc($title) ?></h1>

<p>
    <a href="<?= site_url('admin/dashboard') ?>">Dashboard</a> |
    <a href="<?= site_url('admin/pets') ?>">Manage Pets</a> |
    <a href="<?= site_url('logout') ?>">Logout</a>
</p>

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

<?php if (empty($applications)): ?>
    <p>No adoption applications have been submitted yet.</p>
<?php else: ?>

    <?php foreach ($applications as $application): ?>
        <hr>

        <h2>
            Application #<?= esc($application['id']) ?>:
            <?= esc($application['pet_name']) ?>
        </h2>

        <p>
            <strong>Pet:</strong>
            <?= esc($application['pet_species']) ?>
            <?= esc($application['pet_breed'] ?? 'Unknown breed') ?>
        </p>

        <p>
            <strong>Pet status:</strong>
            <?= esc(ucfirst($application['pet_status'])) ?>
        </p>

        <p>
            <strong>Adopter:</strong>
            <?= esc($application['adopter_name']) ?>
        </p>

        <p>
            <strong>Email:</strong>
            <?= esc($application['adopter_email']) ?>
        </p>

        <p>
            <strong>Phone:</strong>
            <?= esc($application['adopter_phone'] ?? 'Not provided') ?>
        </p>

        <p>
            <strong>Application date:</strong>
            <?= esc($application['application_date']) ?>
        </p>

        <p>
            <strong>Reason for adoption:</strong><br>
            <?= nl2br(esc($application['reason'])) ?>
        </p>

        <p>
            <strong>Current status:</strong>
            <?= esc(ucfirst($application['status'])) ?>
        </p>

        <?php if (! empty($application['admin_notes'])): ?>
            <p>
                <strong>Previous admin notes:</strong><br>
                <?= nl2br(esc($application['admin_notes'])) ?>
            </p>
        <?php endif; ?>

        <?php if ($application['status'] === 'pending'): ?>
            <form
                action="<?= site_url('admin/applications/update/' . $application['id']) ?>"
                method="post"
            >
                <?= csrf_field() ?>

                <label for="notes-<?= esc($application['id']) ?>">
                    Admin notes
                </label><br>

                <textarea
                    id="notes-<?= esc($application['id']) ?>"
                    name="admin_notes"
                    rows="3"
                    maxlength="2000"
                    style="width: 100%; max-width: 500px;"
                    placeholder="Optional review notes"
                ></textarea>

                <br><br>

                <button
                    type="submit"
                    name="status"
                    value="approved"
                    onclick="return confirm('Approve this application and mark the pet as adopted?');"
                >
                    Approve Application
                </button>

                <button
                    type="submit"
                    name="status"
                    value="rejected"
                    onclick="return confirm('Reject this application?');"
                >
                    Reject Application
                </button>
            </form>
        <?php else: ?>
            <p>This application has already been reviewed.</p>
        <?php endif; ?>

    <?php endforeach; ?>

<?php endif; ?>

</body>
</html>
