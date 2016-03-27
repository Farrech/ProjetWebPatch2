# ProjetWebPatch2

Du coup, j'ai ajouté les sections dans les pages que j'ai créées. Pour intégrer le menu, j'ai mis les include_once en début, et pour le footer aussi (je l'ai séparé du menu). J'ai aussi enlevé la section dans ta page menu: c'est le contenu de chaque page qui est inclus enre include_once('menu.php') et include_once(footer.php). J'ai aussi remplacé la couleur de fond, ça me faisait un énorme truc noir pas très beau.
Comme je t'ai dit, les menus correpondent pas à ce que j'ai codé. T'as eu l'exemple des projets, mais il y a aussi connexion/inscription (l'inscription est pas encore fini): j'ai prévu de tout faire sur une page.
Je t'avoue que ça va être un peu galère de tout recoder et séparer en X pages, le mieux ça serait de refaire le menu, par exemple pour les projets juste que ça envoie sur la page gestionProjets.php, ensuite le mec (gestionnaire/prof) choisi si il veux les modifier, les supprimer...
Par contre, la liste des projets c'est pas mal, ça concerne tout le monde.
Tu m'as aussi parlé de la BDD, mais je te l'ai envoyé par mail (envoie moi un sms sinon).
Ah, il y a aussi l'affichage des tableaux: tout est en blanc crème quasi invisible, je sais pas où changer ça dans le css.
Du coup, je vais finir d'ici demain l'inscription, et ajouter des clients/enseignants en plus, comme ça j'enchaine avec la création de groupe.
Par contre, pour l'instant, j'ai du mal à voir l'utilité de la table "Action", on en a pas vraiment besoin je trouve... Voilà, tiens moi au jus.

PS: les pages que je t'envoie sont fonctionelles, mais il y a de la relecture à faire, on peut surement simplifier des choses, mais à voir en dernier, quand tout fonctionneras.

PPS: Désolé, j'ai trouvé un bug dans gestion de projets, une fois un projet modifié, si on voulait faire une autre action après, ça planté totalement, j'ai mis du temps à réparer (de façon très moche). Je t'envoie tout ça quand même, que tu puisse voir un peu la mise en forme des menus, j'essayerais d'optimiser le code au plus vite. Have fun <3
