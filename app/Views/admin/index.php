
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Management | Admin</title>
</head>
<body>

    <h1>Pet Management</h1>
    <p>Manage all pets available in the Pet Adoption Management System.</p>

    <p>
        <a href="<?= site_url('admin/pets/create') ?>">
            + Add New Pet
        </a>
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

    <?php if (session()->getFlashdata('errors')): ?>
        <ul style="color: red;">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (empty($pets)): ?>

        <p>No pets have been added yet.</p>

    <?php else: ?>

        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Species</th>
                    <th>Breed</th>
                    <th>Sex</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($pets as $pet): ?>
                    <tr>
                        <td><?= esc($pet['id']) ?></td>
                        <td><?= esc($pet['name']) ?></td>
                        <td><?= esc($pet['species']) ?></td>
                        <td><?= esc($pet['breed'] ?? 'Not specified') ?></td>
                        <td><?= esc($pet['sex'] ?? 'Not specified') ?></td>
                        <td><?= esc(ucfirst($pet['status'])) ?></td>

                        <td>
                            <a href="<?= site_url('admin/pets/edit/' . $pet['id']) ?>">
                                Edit
                            </a>

                            <form
                                action="<?= site_url('admin/pets/delete/' . $pet['id']) ?>"
                                method="post"
                                style="display: inline;"
                                onsubmit="return confirm('Are you sure you want to delete this pet?');"
                            >
                                <?= csrf_field() ?>

                                <button type="submit">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

    <p>
        <a href="<?= site_url('pets') ?>">View Pet Catalog</a>
        |
        <a href="<?= site_url('logout') ?>">Logout</a>
    </p>

</body>
</html>
