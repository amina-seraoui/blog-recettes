<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <link rel="stylesheet" href="/assets/css/index.min.css" />
        <link rel="icon" href="/uploads/img/logo-min.svg" />
        <title>Nuage de douceurs</title>
    </head>
    <body>
        <header>
            <a href="/" class="logo"><img src="/uploads/img/logo.svg" alt="Logo - Nuage de douceurs"></a>
            <div class="right">
                <div class="search-bar">
                    <form action="#" method="POST">
                        <input type="search">
                    </form>
                    <img src="/uploads/img/search.svg" alt="Rechercher" class="icon">
                </div>
                <div class="menu">
                    <img src="/uploads/img/menu.svg" alt="Menu" class="icon" id="open-menu">
                    <div id="menu">
                        <nav>
                            <img src="/uploads/img/close.svg" alt="Fermer" class="icon" id="close-menu">
                            <?php if (isset($_SESSION['user'])): ?>
                                <ul aria-label="Administration" class="titled">
                                    <li><a href="/admin/recipes">Gestion des recettes</a></li>
                                    <li><a href="/deconnexion">Déconnexion</a></li>
                                </ul>
                            <?php endif; ?>
                            <ul aria-label="Catégories" class="titled">
                                <?php foreach ($categories as $category): ?>
                                    <li><a href="/<?= $category->slug ?>"><?= $category->name ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </header>
        <?= $content ?>
        <footer>
            <div class="container">
                <div class="top">
                    <div>
                        <a href="/" class="logo"><img src="/uploads/img/logo-light.svg" alt="Logo - Nuage de douceurs"></a>
                        <p>
                            Sweet sweet powder caramels pudding jujubes biscuit chupa chups. Topping topping halvah cake pastry pudding bear claw. Cupcake marshmallow sesame snaps sweet roll ice cream chocolate bar jelly beans. Sesame snaps croissant ice cream dragée biscuit tart apple pie.
                        </p>
                    </div>
                    <nav>
                        <ul aria-label="Á propos" class="titled">
                            <li><a href="#">Nous contacter</a></li>
                            <li><a href="#">Carrières</a></li>
                            <li><a href="/connexion">Connexion</a></li>
                        </ul>
                        <ul aria-label="Catégories" class="titled">
                        <?php foreach ($categories as $category): ?>
                            <li><a href="/<?= $category->slug ?>"><?= $category->name ?></a></li>
                        <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
                <hr>
                <div class="bottom">
                    <p>© 2022 Amina Seraoui - Tous droits réservés</p>
                    <div class="socials">
                        <a href="#"><img src="/uploads/img/instagram.svg" alt="Instagram" class="icon"></a>
                        <a href="#"><img src="/uploads/img/facebook.svg" alt="Facebook" class="icon"></a>
                        <a href="#"><img src="/uploads/img/twitter.svg" alt="Twitter" class="icon"></a>
                        <a href="#"><img src="/uploads/img/youtube.svg" alt="Youtube" class="icon"></a>
                    </div>
                </div>
            </div>
        </footer>
    </body>
    <script src="/assets/js/index.min.js"></script>
</html>
