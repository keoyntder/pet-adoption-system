
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
</head>
<body>
    <h1>Add New Pet</h1>

    <?php if (session()->getFlashdata('errors')): ?>
        <ul style="color: red;">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="<?= site_url('admin/pets/store') ?>" method="post">
        <?= csrf_field() ?>

        <p>
            <label for="name">Pet Name *</label><br>
            <input type="text" id="name" name="name"
                value="<?= esc(old('name') ?? '') ?>" required>
        </p>

        <p>
            <label for="species">Species *</label><br>
            <select id="species" name="species" required>
                <option value="">Select species</option>
                <?php foreach (['Dog', 'Cat', 'Rabbit', 'Bird', 'Other'] as $species): ?>
                    <option value="<?= esc($species) ?>"
                        <?= old('species') === $species ? 'selected' : '' ?>>
                        <?= esc($species) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="breed">Breed</label><br>
            <input type="text" id="breed" name="breed"
                value="<?= esc(old('breed') ?? '') ?>">
        </p>

        <p>
            <label for="sex">Sex *</label><br>
            <select id="sex" name="sex" required>
                <option value="">Select sex</option>
                <?php foreach (['Male', 'Female', 'Unknown'] as $sex): ?>
                    <option value="<?= esc($sex) ?>"
                        <?= old('sex') === $sex ? 'selected' : '' ?>>
                        <?= esc($sex) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="age">Age (years)</label><br>
            <input type="number" id="age" name="age" min="0" max="100"
                value="<?= esc(old('age') ?? '') ?>">
        </p>

        <p>
            <label for="size">Size</label><br>
            <input type="text" id="size" name="size"
                value="<?= esc(old('size') ?? '') ?>">
        </p>

        <p>
            <label for="color">Color</label><br>
            <input type="text" id="color" name="color"
                value="<?= esc(old('color') ?? '') ?>">
        </p>

        <p>
            <label for="description">Description</label><br>
            <textarea id="description" name="description" rows="5"><?= esc(old('description') ?? '') ?></textarea>
        </p>

        <p>
            <label for="status">Status *</label><br>
            <select id="status" name="status" required>
                <?php foreach (['available', 'pending', 'adopted', 'unavailable'] as $status): ?>
                    <option value="<?= esc($status) ?>"
                        <?= (old('status') ?? 'available') === $status ? 'selected' : '' ?>>
                        <?= esc(ucfirst($status)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <button type="submit">Save Pet</button>
        <a href="<?= site_url('admin/pets') ?>">Cancel</a>
    </form>
</body>
</html>
