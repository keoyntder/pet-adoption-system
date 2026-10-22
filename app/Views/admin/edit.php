
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
</head>
<body>
    <h1>Edit Pet</h1>

    <?php if (session()->getFlashdata('errors')): ?>
        <ul style="color: red;">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="<?= site_url('admin/pets/update/' . $pet['id']) ?>" method="post">
        <?= csrf_field() ?>

        <p>
            <label for="name">Pet Name *</label><br>
            <input id="name" name="name" required maxlength="100"
                value="<?= esc(old('name') ?? $pet['name']) ?>">
        </p>

        <p>
            <label for="species">Species *</label><br>
            <input id="species" name="species" required maxlength="50"
                value="<?= esc(old('species') ?? $pet['species']) ?>">
        </p>

        <p>
            <label for="breed">Breed</label><br>
            <input id="breed" name="breed" maxlength="100"
                value="<?= esc(old('breed') ?? $pet['breed'] ?? '') ?>">
        </p>

        <p>
            <label for="sex">Sex *</label><br>
            <select id="sex" name="sex" required>
                <?php foreach (['Male', 'Female', 'Unknown'] as $sex): ?>
                    <option value="<?= esc($sex) ?>"
                        <?= (old('sex') ?? $pet['sex']) === $sex ? 'selected' : '' ?>>
                        <?= esc($sex) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="age">Age (years)</label><br>
            <input type="number" id="age" name="age" min="0" max="100"
                value="<?= esc(old('age') ?? $pet['age'] ?? '') ?>">
        </p>

        <p>
            <label for="size">Size</label><br>
            <input id="size" name="size" maxlength="30"
                value="<?= esc(old('size') ?? $pet['size'] ?? '') ?>">
        </p>

        <p>
            <label for="color">Color</label><br>
            <input id="color" name="color" maxlength="100"
                value="<?= esc(old('color') ?? $pet['color'] ?? '') ?>">
        </p>

        <p>
            <label for="description">Description</label><br>
            <textarea id="description" name="description" rows="5"><?= esc(old('description') ?? $pet['description'] ?? '') ?></textarea>
        </p>

        <p>
            <label for="status">Status *</label><br>
            <select id="status" name="status" required>
                <?php foreach (['available', 'pending', 'adopted', 'unavailable'] as $status): ?>
                    <option value="<?= esc($status) ?>"
                        <?= (old('status') ?? $pet['status']) === $status ? 'selected' : '' ?>>
                        <?= esc(ucfirst($status)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <button type="submit">Update Pet</button>
        <a href="<?= site_url('admin/pets') ?>">Cancel</a>
    </form>
</body>
</html>
