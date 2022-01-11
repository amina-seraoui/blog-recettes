<main id="category">
    <figure class="container cover category">
        <img src="uploads/img/category-cover.jpg" alt="Couverture de catégorie">
    </figure>
    <section>
        <h2><?= $category->name ?></h2>
        <div class="recipe-cards">
            <?php foreach ($recipes as $recipe) {
                $recipe->c_slug = $category->slug;
                $recipe->category = $category->name;
                require '_recipe-card.php';
            } ?>
        </div>
        <?= $pagination ?>
    </section>
</main>