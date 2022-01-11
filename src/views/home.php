<main>
    <section id="hero">
        <figure>
            <img src="uploads/img/<?= $first->image ?>" alt="<?= $first->name ?>">
        </figure>
        <article>
            <div class="content">
                <h1><?= $first->name ?></h1>
                <p><?= $first->description ?></p>
            </div>
            <a href="/<?= $first->r_slug ?>" class="btn round">
                <img src="uploads/img/arrow.svg" alt="Fléche directionnelle" class="icon">
            </a>
        </article>
    </section>
    <section>
        <h2>Dernières recettes</h2>
        <div class="recipe-cards">
            <?php foreach ($recipes as $recipe) {
                require '_recipe-card.php';
            } ?>
        </div>
    </section>
</main>