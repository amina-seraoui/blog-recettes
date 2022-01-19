
<a href="https://gitmoji.dev">
  <img src="https://img.shields.io/badge/gitmoji-%20😜%20😍-FFDD67.svg?style=flat-square" alt="Gitmoji">
</a>

# Nuage de douceurs
Un blog de recettes de desserts

## Démo

Une démonstration de l'interface graphique est disponible à l'adresse : https://blog-recettes.vercel.app/. Les pages accessibles sont :
- [l'accueil](https://blog-recettes.vercel.app/),
- [la page recette](https://blog-recettes.vercel.app/recipe.html)
- [la page catégorie](https://blog-recettes.vercel.app/category.html)
- [la page admin](https://blog-recettes.vercel.app/admin.html)
- [la formulaire de création](https://blog-recettes.vercel.app/form.html)

## Installation

### Front
1. Ouvrir le terminal de commande depuis le dossier front.
1. Lancer la commande `npm i`.
1. Lancer la commande `npm run start` pour ouvrir le serveur de développement.
    - Vous pouvez ensuite vous rendre dans le dossier front/src et commencer à développer.
1. Lancer `npm run build` pour générer vos fichiers de production.

### Back
1. Génerer les assets (si ce n'est déjà fait) :
    1. Ouvrir le terminal de commande depuis le dossier front.
    1. Lancer la commande `npm i`.
    1. Lancer `npm run build`.
1. Importer la base de donnée SQL.
1. Modifier si besoin le fichier src/Table/Table.php.
1. Ouvrir le terminal depuis la racine du projet.
1. Lancer la commande `composer dump-autoload`.
1. Lancer la commande `composer start`.

#### /!\ Pré-requis
=> PHP 8

## Design
- [template](https://www.figma.com/file/Beu9scgaUDe9LQsK9mRt6r/Nuage-de-douceurs?node-id=4%3A5)
- [inspiration](https://fabrx.co/preview/tastebite/)
- [images](https://unsplash.com/)

## To-do
- [X] Réaliser le visuel sur figma
- [X] Intégrer la maquette en HTML - CSS - JS
- [X] Configurer les fonctionnalités serveur
- [ ] Bonus : permettre de noter les recettes