
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> | Pet Adoption</title>
</head>
<body>

    <p>
        <a href="<?= site_url('pets') ?>">Back to Pet Catalog</a>
    </p>

    <h1><?= esc($pet['name']) ?></h1>

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

    <?php if (! empty($pet['image'])): ?>
        <img
            src="<?= base_url('uploads/pets/' . $pet['image']) ?>"
            alt="<?= esc($pet['name']) ?>"
            style="max-width: 350px; height: auto;"
        >
    <?php endif; ?>

    <h2>Pet Information</h2>

    <p><strong>Species:</strong> <?= esc($pet['species']) ?></p>
    <p><strong>Breed:</strong> <?= esc($pet['breed'] ?: 'Not specified') ?></p>
    <p><strong>Sex:</strong> <?= esc($pet['sex'] ?: 'Not specified') ?></p>
    <p><strong>Age:</strong> <?= esc($pet['age'] ?: 'Not specified') ?></p>
    <p><strong>Size:</strong> <?= esc($pet['size'] ?: 'Not specified') ?></p>
    <p><strong>Color:</strong> <?= esc($pet['color'] ?: 'Not specified') ?></p>
    <p><strong>Description:</strong> <?= esc($pet['description'] ?: 'No description available.') ?></p>
    <p><strong>Status:</strong> <?= esc(ucfirst($pet['status'])) ?></p>

    <hr>

    <h2>Adoption Application</h2>

    <?php if (session()->get('role') !== 'adopter'): ?>

        <p>Only adopter accounts can submit adoption applications.</p>

    <?php elseif ($pet['status'] !== 'available'): ?>

        <p>This pet is not currently available for adoption.</p>

    <?php elseif ($existingApplication): ?>

        <p>You have already applied to adopt this pet.</p>
        <p>
            <strong>Application status:</strong>
            <?= esc(ucfirst($existingApplication['status'])) ?>
        </p>

    <?php else: ?>

        <form
            action="<?= site_url('pets/apply/' . $pet['id']) ?>"
            method="post"
        >
            <?= csrf_field() ?>

            <div>
                <label for="reason">
                    Why would you like to adopt this pet?
                </label>
                <br>

                <textarea
                    name="reason"
                    id="reason"
                    rows="6"
                    cols="50"
                    minlength="20"
                    maxlength="2000"
                    required
                ><?= esc(old('reason') ?? '') ?></textarea>
            </div>

            <br>

            <button type="submit">
                Submit Adoption Application
            </button>
        </form>

    <?php endif; ?>

    <p>
        <a href="<?= site_url('logout') ?>">Logout</a>
    </p>

</body>
</html>
