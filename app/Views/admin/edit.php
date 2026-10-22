
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
</head>
<body>
    <h1>Edit Pet</h1>

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

    <form
        action="<?= site_url('admin/pets/update/' . $pet['id']) ?>"
        method="post"
        enctype="multipart/form-data"
    >
        <?= csrf_field() ?>

        <p>
            <label for="name">Pet Name *</label><br>
            <input
                id="name"
                name="name"
                required
                maxlength="150"
                value="<?= esc(old('name') ?? $pet['name']) ?>"
            >
        </p>

        <p>
            <label for="species">Species *</label><br>
            <input
                id="species"
                name="species"
                required
                maxlength="100"
                value="<?= esc(old('species') ?? $pet['species']) ?>"
            >
        </p>

        <p>
            <label for="breed">Breed</label><br>
            <input
                id="breed"
                name="breed"
                maxlength="100"
                value="<?= esc(old('breed') ?? $pet['breed'] ?? '') ?>"
            >
        </p>

        <p>
            <label for="sex">Sex *</label><br>
            <select id="sex" name="sex" required>
                <?php foreach (['Male', 'Female', 'Unknown'] as $sex): ?>
                    <option
                        value="<?= esc($sex) ?>"
                        <?= (old('sex') ?? $pet['sex']) === $sex ? 'selected' : '' ?>
                    >
                        <?= esc($sex) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="age">Age (years)</label><br>
            <input
                type="number"
                id="age"
                name="age"
                min="0"
                max="100"
                value="<?= esc(old('age') ?? $pet['age'] ?? '') ?>"
            >
        </p>

        <p>
            <label for="size">Size</label><br>
            <input
                id="size"
                name="size"
                maxlength="50"
                value="<?= esc(old('size') ?? $pet['size'] ?? '') ?>"
            >
        </p>

        <p>
            <label for="color">Color</label><br>
            <input
                id="color"
                name="color"
                maxlength="100"
                value="<?= esc(old('color') ?? $pet['color'] ?? '') ?>"
            >
        </p>

        <p>
            <label for="description">Description</label><br>
            <textarea
                id="description"
                name="description"
                rows="5"
                maxlength="5000"
            ><?= esc(old('description') ?? $pet['description'] ?? '') ?></textarea>
        </p>

        <hr>

        <h2>Pet Image</h2>

        <?php if (! empty($pet['image'])): ?>
            <p>Current image:</p>
            <img
                src="<?= base_url('uploads/pets/' . $pet['image']) ?>"
                alt="<?= esc($pet['name']) ?>"
                style="width: 220px; height: 180px; object-fit: cover;"
            >
        <?php else: ?>
            <p>No image uploaded for this pet yet.</p>
        <?php endif; ?>

        <p>
            <label for="image">Upload a New Image</label><br>
            <input
                type="file"
                id="image"
                name="image"
                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
            >
            <br>
            <small>
                Accepted formats: JPG, PNG, WEBP. Maximum size: 5 MB.
                Leave blank to keep the current image.
            </small>
        </p>

        <p>
            <label for="status">Status *</label><br>
            <select id="status" name="status" required>
                <?php foreach (['available', 'unavailable', 'adopted'] as $status): ?>
                    <option
                        value="<?= esc($status) ?>"
                        <?= (old('status') ?? $pet['status']) === $status ? 'selected' : '' ?>
                    >
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
