<form method="POST" enctype="multipart/form-data">
    <div class="field required">
        <label for="name">Titre :</label>
        <input type="text" id="name" name="name" value="<?= $item['name'] ?? '' ?>"/>
    </div>
    <div class="field required">
        <label for="slug">Slug :</label>
        <input type="text" id="slug" name="slug" value="<?= $item['slug'] ?? '' ?>"/>
    </div>
    <div class="field">
        <label for="description">Description :</label>
        <textarea id="description" name="description"><?= $item['description'] ?? '' ?></textarea>
    </div>
    <fieldset class="upload image">
        <legend>Image :</legend>
        <label for="image" <?= isset($item['image']) ? 'style="background: center / cover url(/uploads/img/thumbs/' . $item['image'] . ')"' : '' ?>/>
        <input type="file" id="image" name="image" accept="image/*" value="<?= $item['image'] ?? '' ?>"/>
    </fieldset>
    <div class="field required">
        <label for="level">Difficulté :</label>
        <select name="level" id="level">
            <?php foreach (LEVELS as $k => $level): ?>
                <option value="<?= $k ?>" <?= isset($item['level']) && $item['level'] === $k ? ' selected' : '' ?>><?= ucfirst($level) ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <div class="field required">
        <label for="prep_time">Temps de préparation :</label>
        <input type="number" name="prep_time" id="prep_time" value="<?= $item['prep_time'] ?? '' ?>" />
    </div>
    <div class="field required">
        <label for="cook_time">Temps de cuisson :</label>
        <input type="number" name="cook_time" id="cook_time" value="<?= $item['cook_time'] ?? '' ?>" />
    </div>
    <fieldset class="array js-todo" data-name="ingredients" data-label="Ingrédients">
    <?= $item['ingredients'] ?? '[]' ?>
    </fieldset>
    <fieldset class="array js-todo" data-name="preparation" data-label="Préparation">
    <?= $item['preparation'] ?? '[]' ?>
    </fieldset>
    <div class="field required">
        <label for="category_id">Catégorie :</label>
        <select name="category_id" id="category_id">
            <?php foreach ($categories as $k => $category): ?>
                <option value="<?= $k ?>" <?= isset($item['category_id']) && $item['category_id'] === $k ? ' selected' : '' ?>><?= $category ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <input type="submit" value="Enregistrer" class="btn primary"/>
</form>