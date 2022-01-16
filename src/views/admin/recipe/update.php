<main id="admin">
    <section>
        <h1>Administration</h1>
        <h2>Modifier la recette n°<?= $item['id'] ?> : <?= $item['name'] ?></h2>
        <div id="recipe-form">
            <?php require '_form.php' ?>
        </div>
    </section>
</main>