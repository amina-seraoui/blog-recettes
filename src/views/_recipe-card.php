<article class="recipe-card">
    <a href="/<?= $recipe->r_slug ?>" class="recipe-card-image">
        <img src="/uploads/img/thumbs/<?= $recipe->image ?>" alt="<?= $recipe->name ?>">
    </a>
    <div class="recipe-card-content">
        <span class="badge level-<?= $recipe->level ?>"><?= LEVELS[$recipe->level] ?></span>
        <a href="/<?= $recipe->r_slug ?>"><h3 class="recipe-card-title"><?= $recipe->name ?></h3></a>
        <div class="js-stars" data-note="4"></div>
        <a href="/<?= $recipe->c_slug ?>" class="recipe-card-link"><?= $recipe->category ?></a>
    </div>
</article>