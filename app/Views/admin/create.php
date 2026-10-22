
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Pet | Pet Adoption Admin</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 30px 20px;
            font-family: Arial, sans-serif;
            background: #f4f7f5;
            color: #26352c;
        }

        .container {
            max-width: 850px;
            margin: auto;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        h1 { margin: 0 0 8px; }
        .subtitle { color: #68766d; margin: 0; }

        .btn {
            display: inline-block;
            padding: 11px 16px;
            border: none;
            border-radius: 7px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-back { background: #e4ebe6; color: #244c34; }
        .btn-save { background: #1b5e3a; color: white; }
        .btn-save:hover { background: #123d26; }

        .panel {
            padding: 28px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(0,0,0,.05);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .field { display: flex; flex-direction: column; gap: 8px; }
        .full-width { grid-column: 1 / -1; }

        label { font-size: 14px; font-weight: bold; }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d6dfd8;
            border-radius: 7px;
            font: inherit;
            background: white;
        }

        textarea { resize: vertical; min-height: 130px; }
        input:focus, select:focus, textarea:focus {
            outline: 2px solid #b6d9c1;
            border-color: #1b5e3a;
        }

        .help { color: #6d796f; font-size: 12px; }

        .alert {
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .alert-error { background: #fff0ef; color: #a12622; }
        .alert-success { background: #e8f5ec; color: #1b5e3a; }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 25px;
        }

        @media (max-width: 600px) {
            .form-grid { grid-template-columns: 1fr; }
            .full-width { grid-column: auto; }
            .panel { padding: 20px; }
        }
    </style>
</head>
<body>
<div class="container">

    <div class="topbar">
        <div>
            <h1>Add a Pet</h1>
            <p class="subtitle">Enter the pet's information for the adoption catalog.</p>
        </div>

        <a href="<?= site_url('admin/pets') ?>" class="btn btn-back">
            &larr; Back to Pets
        </a>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php $errors = session()->getFlashdata('errors') ?? []; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <strong>Please correct the following:</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="panel">
        <form action="<?= site_url('admin/pets/store') ?>"
              method="post"
              enctype="multipart/form-data">

            <?= csrf_field() ?>

            <div class="form-grid">

                <div class="field">
                    <label for="name">Pet Name *</label>
                    <input type="text" id="name" name="name"
                           value="<?= esc(old('name') ?? '') ?>"
                           maxlength="100" required>
                </div>

                <div class="field">
                    <label for="species">Species *</label>
                    <select id="species" name="species" required>
                        <option value="">Select species</option>
                        <?php foreach (['Dog', 'Cat', 'Rabbit', 'Bird', 'Other'] as $species): ?>
                            <option value="<?= esc($species) ?>"
                                <?= old('species') === $species ? 'selected' : '' ?>>
                                <?= esc($species) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field">
                    <label for="breed">Breed</label>
                    <input type="text" id="breed" name="breed"
                           value="<?= esc(old('breed') ?? '') ?>">
                </div>

                <div class="field">
                    <label for="sex">Sex *</label>
                    <select id="sex" name="sex" required>
                        <option value="">Select sex</option>
                        <option value="Male" <?= old('sex') === 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= old('sex') === 'Female' ? 'selected' : '' ?>>Female</option>
                        <option value="Unknown" <?= old('sex') === 'Unknown' ? 'selected' : '' ?>>Unknown</option>
                    </select>
                </div>

                <div class="field">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age"
                           min="0" max="300"
                           value="<?= esc(old('age') ?? '') ?>"
                           placeholder="Age in months">
                    <span class="help">Enter age in months if known.</span>
                </div>

                <div class="field">
                    <label for="size">Size *</label>
                    <select id="size" name="size" required>
                        <option value="">Select size</option>
                        <?php foreach (['Small', 'Medium', 'Large', 'Extra Large', 'Unknown'] as $size): ?>
                            <option value="<?= esc($size) ?>"
                                <?= old('size') === $size ? 'selected' : '' ?>>
                                <?= esc($size) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field">
                    <label for="color">Color</label>
                    <input type="text" id="color" name="color"
                           value="<?= esc(old('color') ?? '') ?>">
                </div>

                <div class="field">
                    <label for="status">Adoption Status *</label>
                    <select id="status" name="status" required>
                        <option value="available"
                            <?= old('status', 'available') === 'available' ? 'selected' : '' ?>>
                            Available
                        </option>
                        <option value="unavailable"
                            <?= old('status') === 'unavailable' ? 'selected' : '' ?>>
                            Unavailable
                        </option>
                        <option value="adopted"
                            <?= old('status') === 'adopted' ? 'selected' : '' ?>>
                            Adopted
                        </option>
                    </select>
                </div>

                <div class="field full-width">
                    <label for="image">Pet Photo</label>
                    <input type="file" id="image" name="image"
                           accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                    <span class="help">Optional. JPG, PNG, or WebP; maximum 5 MB.</span>
                </div>

                <div class="field full-width">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description"
                              required><?= esc(old('description') ?? '') ?></textarea>
                </div>

            </div>

            <div class="form-actions">
                <a href="<?= site_url('admin/pets') ?>" class="btn btn-back">Cancel</a>
                <button type="submit" class="btn btn-save">Save Pet</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
