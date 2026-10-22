
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
</head>
<body>

    <h1>Pet Adoption Management System</h1>
    <h2>Admin Dashboard</h2>

    <p>Welcome, <?= esc(session()->get('full_name')) ?>!</p>

    <hr>

    <h3>System Overview</h3>

    <ul>
        <li>Total Pets: <?= esc($totalPets) ?></li>
        <li>Total Users: <?= esc($totalUsers) ?></li>
        <li>Total Adoption Applications: <?= esc($totalApplications) ?></li>
        <li>Pending Applications: <?= esc($pendingApplications) ?></li>
    </ul>

    <h3>Management</h3>

    <ul>
        <li><a href="<?= site_url('admin/pets') ?>">Manage Pets</a></li>
        <li><a href="<?= site_url('pets') ?>">View Pet Catalog</a></li>
        <li> <a href="<?= site_url('admin/applications') ?>">
        Manage Adoption Applications</a></li>
        <li><a href="<?= site_url('logout') ?>">Logout</a></li>
    </ul>

</body>
</html>
