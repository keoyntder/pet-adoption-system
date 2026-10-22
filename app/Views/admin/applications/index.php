
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Manage Applications') ?></title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background: #f4f7f4;
            color: #24352b;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
        }

        .header h1 {
            margin-bottom: 8px;
        }

        nav a {
            display: inline-block;
            margin: 5px 12px 5px 0;
            color: #17683b;
            text-decoration: none;
            font-weight: bold;
        }

        .message {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .success {
            color: #17683b;
            background: #e0f4e5;
        }

        .error {
            color: #a32121;
            background: #fde8e8;
        }

        .application-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 22px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .06);
        }

        .application-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }

        .application-header h2 {
            margin: 0 0 8px;
        }

        .status {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .pending {
            background: #fff0c2;
            color: #805900;
        }

        .approved {
            background: #d8f3df;
            color: #17683b;
        }

        .rejected {
            background: #fde1e1;
            color: #a32121;
        }

        .details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .detail {
            background: #f7f9f7;
            padding: 14px;
            border-radius: 8px;
            overflow-wrap: anywhere;
        }

        .detail strong {
            display: block;
            margin-bottom: 7px;
            color: #526457;
            font-size: 13px;
        }

        .reason {
            margin: 15px 0;
            line-height: 1.7;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        .review-form {
            border-top: 1px solid #e5ebe6;
            margin-top: 20px;
            padding-top: 20px;
        }

        label {
            display: block;
            margin: 12px 0 7px;
            font-weight: bold;
        }

        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccd6ce;
            border-radius: 7px;
            font: inherit;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        .button {
            display: inline-block;
            border: 0;
            border-radius: 7px;
            padding: 12px 18px;
            margin-top: 14px;
            background: #17683b;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .button:hover {
            background: #104c2b;
        }

        .reviewed-note {
            padding: 14px;
            background: #f1f5f1;
            border-radius: 8px;
            line-height: 1.6;
        }

        .empty-state {
            background: white;
            text-align: center;
            padding: 45px 20px;
            border-radius: 12px;
        }

        @media (max-width: 600px) {
            body {
                padding: 16px;
            }

            .application-card {
                padding: 17px;
            }
        }
    </style>
</head>
<body>

<div class="container">

    <header class="header">
        <div>
            <h1>Adoption Applications</h1>
            <p>Review applicants and manage adoption requests.</p>
        </div>

        <nav>
            <a href="<?= site_url('admin/dashboard') ?>">Dashboard</a>
            <a href="<?= site_url('admin/pets') ?>">Manage Pets</a>
            <a href="<?= site_url('logout') ?>">Logout</a>
        </nav>
    </header>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="message success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="message error">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($applications)): ?>

        <div class="empty-state">
            <h2>No applications found</h2>
            <p>Adoption applications will appear here when adopters submit requests.</p>
        </div>

    <?php else: ?>

        <?php foreach ($applications as $application): ?>

            <article class="application-card">

                <div class="application-header">
                    <div>
                        <h2>Application #<?= esc($application['id']) ?></h2>
                        <p>
                            Submitted:
                            <?= esc($application['application_date']) ?>
                        </p>
                    </div>

                    <span class="status <?= esc($application['status']) ?>">
                        <?= esc(ucfirst($application['status'])) ?>
                    </span>
                </div>

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
                    </div>

                    <div class="detail">
                        <strong>Species</strong>
                        <?= esc($application['pet_species']) ?>
                    </div>

                    <div class="detail">
                        <strong>Breed</strong>
                        <?= esc($application['pet_breed'] ?: 'Unknown') ?>
                    </div>

                    <div class="detail">
                        <strong>Pet availability</strong>
                        <?= esc(ucfirst($application['pet_status'])) ?>
                    </div>

                </div>

                <div class="reason">
                    <strong>Reason for adoption</strong>
                    <p><?= esc($application['reason']) ?></p>
                </div>

                <?php if ($application['admin_notes']): ?>
                    <div class="reason">
                        <strong>Administrator notes</strong>
                        <p><?= esc($application['admin_notes']) ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($application['status'] === 'pending'): ?>

                    <form
                        class="review-form"
                        method="post"
                        action="<?= site_url('admin/applications/update/' . $application['id']) ?>"
                    >
                        <?= csrf_field() ?>

                        <label for="status-<?= esc($application['id']) ?>">
                            Review decision
                        </label>

                        <select
                            id="status-<?= esc($application['id']) ?>"
                            name="status"
                            required
                        >
                            <option value="">Select a decision</option>
                            <option value="approved">Approve application</option>
                            <option value="rejected">Reject application</option>
                        </select>

                        <label for="notes-<?= esc($application['id']) ?>">
                            Administrator notes (optional)
                        </label>

                        <textarea
                            id="notes-<?= esc($application['id']) ?>"
                            name="admin_notes"
                            maxlength="2000"
                            placeholder="Enter notes for this application..."
                        ></textarea>

                        <button class="button" type="submit">
                            Submit Review
                        </button>
                    </form>

                <?php else: ?>

                    <div class="reviewed-note">
                        This application has already been
                        <strong><?= esc($application['status']) ?></strong>.
                    </div>

                <?php endif; ?>

            </article>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

</body>
</html>
