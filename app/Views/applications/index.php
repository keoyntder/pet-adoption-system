
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Manage Applications') ?></title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background: #f4f7f4;
            color: #24352b;
        }

        .container { max-width: 1300px; margin: auto; }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
        }

        .header h1 { margin: 0 0 8px; }

        a { color: #17683b; text-decoration: none; font-weight: bold; }

        .message {
            padding: 12px 16px;
            margin-bottom: 15px;
            border-radius: 7px;
            background: #e0f4e5;
        }

        .error { background: #ffe7e7; color: #a32121; }

        .application {
            background: white;
            padding: 22px;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,.06);
        }

        .application h2 { margin-top: 0; }

        .details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
        }

        .detail {
            padding: 12px;
            background: #f6f8f6;
            border-radius: 7px;
            overflow-wrap: anywhere;
        }

        .detail strong { display: block; margin-bottom: 6px; }

        .reason {
            padding: 14px;
            margin: 15px 0;
            background: #f6f8f6;
            border-radius: 7px;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            background: #fff0cc;
            color: #795500;
            font-weight: bold;
            text-transform: capitalize;
        }

        .status.approved { background: #dff5e5; color: #17683b; }
        .status.rejected { background: #ffe3e3; color: #a32121; }

        label { display: block; margin: 12px 0 6px; font-weight: bold; }

        select, textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccd6ce;
            border-radius: 7px;
            font: inherit;
        }

        textarea { min-height: 90px; resize: vertical; }

        button {
            margin-top: 12px;
            padding: 11px 18px;
            border: 0;
            border-radius: 7px;
            background: #17683b;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .empty {
            padding: 35px;
            text-align: center;
            background: white;
            border-radius: 10px;
        }

        @media (max-width: 600px) {
            body { padding: 15px; }
        }
    </style>
</head>
<body>
<div class="container">

    <header class="header">
        <div>
            <h1>Adoption Applications</h1>
            <p>Review applications submitted by adopters.</p>
        </div>

        <nav>
            <a href="<?= site_url('admin/dashboard') ?>">Dashboard</a>
            &nbsp; | &nbsp;
            <a href="<?= site_url('admin/pets') ?>">Manage Pets</a>
            &nbsp; | &nbsp;
            <a href="<?= site_url('logout') ?>">Logout</a>
        </nav>
    </header>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="message">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="message error">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($applications)): ?>

        <div class="empty">
            <h2>No applications found</h2>
            <p>There are currently no adoption applications to display.</p>
        </div>

    <?php else: ?>

        <p>Total applications: <strong><?= count($applications) ?></strong></p>

        <?php foreach ($applications as $application): ?>
            <article class="application">

                <h2>
                    Application #<?= esc($application['id']) ?>
                    — <?= esc($application['pet_name']) ?>
                </h2>

                <p>
                    Status:
                    <span class="status <?= esc(strtolower($application['status'])) ?>">
                        <?= esc($application['status']) ?>
                    </span>
                </p>

                <div class="details">
                    <div class="detail">
                        <strong>Adopter</strong>
                        <?= esc($application['adopter_name']) ?>
                    </div>

                    <div class="detail">
                        <strong>Email</strong>
                        <?= esc($application['adopter_email']) ?>
                    </div>

                    <div class="detail">
                        <strong>Phone</strong>
                        <?= esc($application['adopter_phone'] ?: 'Not provided') ?>
                    </div>

                    <div class="detail">
                        <strong>Pet</strong>
                        <?= esc($application['pet_name']) ?>
                        (<?= esc($application['pet_species']) ?>)
                    </div>

                    <div class="detail">
                        <strong>Application Date</strong>
                        <?= esc($application['application_date']) ?>
                    </div>

                    <div class="detail">
                        <strong>Pet Status</strong>
                        <?= esc($application['pet_status']) ?>
                    </div>
                </div>

                <h3>Reason for Adoption</h3>
                <div class="reason"><?= esc($application['reason']) ?></div>

                <?php if (in_array($application['status'], ['pending'], true)): ?>
                    <form
                        action="<?= site_url('admin/applications/update/' . $application['id']) ?>"
                        method="post"
                    >
                        <?= csrf_field() ?>

                        <label for="status-<?= esc($application['id']) ?>">
                            Update Application Status
                        </label>

                        <select
                            id="status-<?= esc($application['id']) ?>"
                            name="status"
                            required
                        >
                            <option value="approved">Approve</option>
                            <option value="rejected">Reject</option>
                        </select>

                        <label for="notes-<?= esc($application['id']) ?>">
                            Admin Notes (optional)
                        </label>

                        <textarea
                            id="notes-<?= esc($application['id']) ?>"
                            name="admin_notes"
                            maxlength="2000"
                            placeholder="Enter notes for this application..."
                        ><?= esc($application['admin_notes'] ?? '') ?></textarea>

                        <button type="submit">Save Decision</button>
                    </form>
                <?php else: ?>
                    <p><strong>Admin Notes:</strong>
                        <?= esc($application['admin_notes'] ?: 'No notes provided') ?>
                    </p>
                <?php endif; ?>

            </article>
        <?php endforeach; ?>

    <?php endif; ?>

</div>
</body>
</html>
