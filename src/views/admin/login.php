<main id="admin">
    <section>
        <h1>Administration</h1>
        <h2>Connexion</h2>
        <form method="POST">
            <div class="field">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" value="<?= $item['username'] ?? '' ?>" name="username" id="username">
            </div>
            <div class="field">
                <label for="password">Mot de passe :</label>
                <input type="password" value="<?= $item['password'] ?? '' ?>" name="password" id="password">
            </div>
            <input type="submit" class="btn primary" value="Connexion">
        </form>
    </section>
</main>