<main id="recipe">
    <section>
        <h1><?= $recipe->name ?></h1>
        <div class="js-stars" data-note="<?= $recipe->note ?? 0 ?>" data-recipe="<?= $recipe->id ?>"></div>
        <hr>
        <p><?= $recipe->description ?></p>
        <figure class="cover recipe"><img src="/uploads/img/<?= $recipe->image ?>" alt="<?= $recipe->name ?>"></figure>
        <div class="indications">
            <div class="indication">
                <span class="label">Tmp. de prép.</span>
                <span class="value"><?= $recipe->prep_time ?> min.</span>
            </div>
            <hr class="vr">
            <div class="indication">
                <span class="label">Tmp. de cuiss.</span>
                <span class="value"><?= $recipe->cook_time ?> min.</span>
            </div>
            <hr class="vr">
            <div class="indication">
            <span class="badge level-<?= $recipe->level ?>"><?= LEVELS[$recipe->level] ?></span>
            </div>
        </div>
    </section>
    <section>
        <article id="ingredients">
            <h2>Ingrédients</h2>
            <ul class="tasks">
                <?php foreach(json_decode($recipe->ingredients) as $ingredient): ?>
                    <li><?= $ingredient ?></li>
                <?php endforeach; ?>
            </ul>
        </article>
        <article id="preparation">
            <h2>Préparation</h2>
            <ol>
            <?php foreach(json_decode($recipe->preparation) as $step): ?>
                    <li><?= $step ?></li>
                <?php endforeach; ?>
            </ol>
        </article>
        <div id="suggestions">
            <h2>Dernières recettes</h2>
            <div class="recipe-cards">
                <?php foreach ($recipes as $recipe) {
                    require '_recipe-card.php';
                } ?>
            </div>
        </div>
    </section>
</main>