<main id="admin">
    <section>
        <h1>Administration</h1>
        <h2>Recettes</h2>
        <a href="./recipes/new" class="btn primary">Ajouter une nouvelle recette</a>
        <div class="recipe-cards">
            <?php foreach($recipes as $recipe): ?>
                <article class="recipe-admin-card">
                    <a href="/<?= $recipe->slug ?>" class="recipe-card-image">
                        <img src="/uploads/img/thumbs/<?= $recipe->image ?>" alt="<?= $recipe->name ?>">
                    </a>
                    <div class="recipe-card-content">
                        <div class="recipe-card-header">
                            <a href="/<?= $recipe->slug ?>"><h3 class="recipe-card-title"><?= $recipe->name ?></h3></a>
                            <a href="./categories/<?= $recipe->c_id ?>" class="recipe-card-subtitle"><?= $recipe->category ?></a>
                        </div>
                        <div class="recipe-card-footer">
                            <a href="/admin/recipes/<?= $recipe->id ?>" class="btn primary">Modifier</a>
                            <form action="/admin/recipes/delete/<?= $recipe->id ?>" method="POST">
                                <input type="hidden" value="1">
                                <input type="submit" class="btn danger" value="Supprimer">
                            </form>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        <?= $pagination ?>                
    </section>
</main>