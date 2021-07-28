<?php
/**
 * 2014 - 2021 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License X11
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/mit-license.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2021 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.3.0
 *
 */

return array (
'releases' =>
array (
0 =>
array (
'version' => '0.1.0',
'date' => '13/09/2019',
'description' => 'Création du module.',
'notes' =>
array (
0 =>
array (
'type' => 'ADD',
'code' => 'PGX-1924',
'text' => 'Implémentation de l\'envoie de la facture.',
),
1 =>
array (
'type' => 'ADD',
'code' => 'PGX-1244',
'text' => 'Création de la page erreur.',
),
2 =>
array (
'type' => 'ADD',
'code' => 'PGX-1243',
'text' => 'Affichage du mode insite.',
),
3 =>
array (
'type' => 'ADD',
'code' => 'PGX-1226',
'text' => 'Configuration des frais de port non-éligibles.',
),
4 =>
array (
'type' => 'ADD',
'code' => 'PGX-1242',
'text' => 'Affichage des boutons de paiements.',
),
5 =>
array (
'type' => 'ADD',
'code' => 'PGX-1624',
'text' => 'Intégration du paiement Paygreen.',
),
6 =>
array (
'type' => 'ADD',
'code' => 'PGX-1607',
'text' => 'Implémentation de la suppression des boutons de paiement.',
),
7 =>
array (
'type' => 'ADD',
'code' => 'PGX-1606',
'text' => 'Implémentation de la création des boutons de paiement.',
),
8 =>
array (
'type' => 'ADD',
'code' => 'PGX-1601',
'text' => 'Ajout du header de l\'extension Paiement.',
),
9 =>
array (
'type' => 'ADD',
'code' => 'PGX-1556',
'text' => 'Ajout de la page de configuration des boutons de paiement.',
),
10 =>
array (
'type' => 'ADD',
'code' => 'PGX-1577',
'text' => 'Création du bouton de paiement par défaut.',
),
11 =>
array (
'type' => 'ADD',
'code' => 'PGX-1555',
'text' => 'Action du bouton de déconnexion.',
),
12 =>
array (
'type' => 'ADD',
'code' => 'PGX-1554',
'text' => 'Ajout de la page de gestion du compte.',
),
13 =>
array (
'type' => 'ADD',
'code' => 'PGX-1224',
'text' => 'Ajout de la page de configuration.',
),
14 =>
array (
'type' => 'ADD',
'code' => 'PGX-1218',
'text' => 'Création des boutons de tests.',
),
15 =>
array (
'type' => 'ADD',
'code' => 'PGX-1227',
'text' => 'Ajout de la page de téléchargement des log.',
),
16 =>
array (
'type' => 'FIX',
'code' => 'PGX-1923',
'text' => 'Correction du double mail de validation.',
),
17 =>
array (
'type' => 'FIX',
'code' => 'PGX-1917',
'text' => 'Correction de la redirection.',
),
18 =>
array (
'type' => 'PERF',
'code' => 'PGX-1584',
'text' => 'Factorisation du code css.',
),
),
'dependencies' =>
array (
'framework' =>
array (
'version' => '1.5.0',
'from' => null,
),
),
),
1 =>
array (
'version' => '0.1.1',
'date' => '20/09/2019',
'description' => 'Améliorations divers',
'notes' =>
array (
0 =>
array (
'type' => 'ADD',
'text' => 'Ajouts et modifications de traductions.',
),
),
'dependencies' =>
array (
'framework' =>
array (
'version' => '1.5.2',
'from' => '1.5.0',
),
),
),
2 =>
array (
'version' => '0.2.0',
'date' => '30/09/2019',
'description' => 'Améliorations divers',
'notes' =>
array (
0 =>
array (
'type' => 'ADD',
'code' => 'PGX-1991',
'text' => 'Upgrade du module',
),
1 =>
array (
'type' => 'FIX',
'text' => 'Correction du nom de la table des transactions.',
),
),
'dependencies' =>
array (
'framework' =>
array (
'version' => '1.5.2',
),
),
),
3 =>
array (
'version' => '0.2.1',
'date' => '30/09/2019',
'description' => 'Améliorations divers',
'dependencies' =>
array (
'framework' =>
array (
'version' => '1.5.4',
'from' => '1.5.2',
),
),
),
4 =>
array (
'version' => '0.3.0',
'date' => '16/10/2019',
'description' => 'Gestion des remboursements.',
'notes' =>
array (
0 =>
array (
'type' => 'ADD',
'code' => 'PGX-2111',
'text' => 'Ajout d\'option pour activer ou désactiver le remboursement.',
),
1 =>
array (
'type' => 'ADD',
'code' => 'PGX-2006',
'text' => 'Activation de la prise en charge des remboursements.',
),
2 =>
array (
'type' => 'ADD',
'text' => 'Synchronisation des remboursements entre la boutique et l\'API.',
),
3 =>
array (
'type' => 'ADD',
'text' => 'Il n\'est désormais plus obligatoire d\'avoir un rib renseigné pour récupérer les informations de son compte depuis l\'API.',
),
4 =>
array (
'type' => 'ADD',
'text' => 'Possibilité d\'ajouter le préfix \'SB\' à la clé publique pour se connecter en sandbox.',
),
5 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'une restriction sur les remboursements, seuls les paiements CASH et TOKENIZE peuvent être remboursés.',
),
6 =>
array (
'type' => 'FIX',
'text' => 'Corrections divers.',
),
7 =>
array (
'type' => 'FIX',
'code' => 'PGI-890',
'text' => 'Correction d\'une erreur lié au SSL.',
),
8 =>
array (
'type' => 'PERF',
'text' => 'Amélioration de la gestion des transactions.',
),
),
'dependencies' =>
array (
'framework' =>
array (
'version' => '1.6.0',
'from' => '1.5.4',
),
),
),
5 =>
array (
'version' => '0.3.1',
'date' => '31/10/2019',
'description' => 'Améliorations divers',
'notes' =>
array (
0 =>
array (
'type' => 'ADD',
'code' => 'PGX-2274',
'text' => 'Condiguration du texte des boutons de paiement.',
),
1 =>
array (
'type' => 'ADD',
'code' => 'PGX-2178',
'text' => 'Amélioration de la documentation sur les frais de ports exclus.',
),
2 =>
array (
'type' => 'ADD',
'code' => 'PGX-2177',
'text' => 'Amélioration de la documentation sur les montants élligibles.',
),
3 =>
array (
'type' => 'ADD',
'code' => 'PGX-2187',
'text' => 'Dissimulation du bloc Paygreen si aucun bouton de paiement disponible.',
),
4 =>
array (
'type' => 'ADD',
'code' => 'PGX-2165',
'text' => 'Ajout d\'un lien vers la page de connexion.',
),
5 =>
array (
'type' => 'ADD',
'code' => 'PGX-2170',
'text' => 'Traduction des messages d\'erreur.',
),
6 =>
array (
'type' => 'ADD',
'code' => 'PGX-2196',
'text' => 'Annulation de la commande lors d\'un paiement refusé.',
),
7 =>
array (
'type' => 'ADD',
'code' => 'PGX-2173',
'text' => 'Limitation de la taille du label des boutons de paiement.',
),
8 =>
array (
'type' => 'ADD',
'code' => 'PGX-2018',
'text' => 'Ajout d\'un message en cas de paiement refusé.',
),
9 =>
array (
'type' => 'ADD',
'text' => 'Ajout de messages pour aider à la configuration des montants éligibles.',
),
10 =>
array (
'type' => 'ADD',
'text' => 'Ajout de nouvelles traductions.',
),
11 =>
array (
'type' => 'ADD',
'text' => 'Vérification de la validité des moyens de paiements avant d\'autoriser le paiement via PayGreen.',
),
12 =>
array (
'type' => 'FIX',
'code' => 'PGX-2261',
'text' => 'Correction d\'erreur sur le formulaire de gestion des boutons de paiement.',
),
13 =>
array (
'type' => 'FIX',
'code' => 'PGX-2273',
'text' => 'Correction du message de paiement refusé.',
),
14 =>
array (
'type' => 'FIX',
'code' => 'PGX-2293',
'text' => 'Dissimulation de la configuration du mode test.',
),
15 =>
array (
'type' => 'FIX',
'code' => 'PGX-2275',
'text' => 'Correction erreur 404 sur la page system.',
),
16 =>
array (
'type' => 'FIX',
'code' => 'PGX-2168',
'text' => 'Correction du css des menus.',
),
17 =>
array (
'type' => 'FIX',
'code' => 'PGX-2181',
'text' => 'Correction des notifications de confirmation.',
),
18 =>
array (
'type' => 'FIX',
'code' => 'PGX-2176',
'text' => 'Correction du paiement avec un utilisateur non enregistré.',
),
19 =>
array (
'type' => 'FIX',
'code' => 'PGX-2172',
'text' => 'Correction de la hauteur du bouton.',
),
20 =>
array (
'type' => 'FIX',
'code' => 'PGX-2164',
'text' => 'Correction du problème de requete vide.',
),
21 =>
array (
'type' => 'FIX',
'code' => 'PGI-888',
'text' => 'Correction d\'une erreur lors du paiement avec un utilisateur non connecté.',
),
22 =>
array (
'type' => 'FIX',
'code' => 'PGI-889',
'text' => 'Correction d\'une erreur lors d\'un paiement RECURRING.',
),
),
'dependencies' =>
array (
'framework' =>
array (
'version' => '1.6.2',
'from' => '1.6.0',
),
),
),
6 =>
array (
'version' => '0.3.2',
'date' => '08/11/2019',
'description' => 'Améliorations divers',
'notes' =>
array (
0 =>
array (
'type' => 'ADD',
'code' => 'PGX-2402',
'text' => 'Ajout selection de moyen de paiement.',
),
1 =>
array (
'type' => 'ADD',
'text' => 'Ajout de nouvelles traductions.',
),
2 =>
array (
'type' => 'ADD',
'text' => 'Possibilité de configurer l\'annulation d\'une transaction en cas de paiement refusé.',
),
3 =>
array (
'type' => 'FIX',
'text' => 'Suppression du type-hinting sur certaines méthodes.',
),
4 =>
array (
'type' => 'FIX',
'code' => 'PGI-879',
'text' => 'Correction d\'un message d\'erreur indiquant que le paiement RECURRING ne pouvait pas être décalé alors que cela concerné le paiement XTIME.',
),
5 =>
array (
'type' => 'FIX',
'code' => 'PGI-883',
'text' => 'Correction d\'un bug de corruption du fichier autoload.cache.php.',
),
6 =>
array (
'type' => 'FIX',
'code' => 'PGI-903',
'text' => 'Correction d\'un bug au niveau du PID ranking lié au code legacy de l\'API PayGreen.',
),
7 =>
array (
'type' => 'PERF',
'text' => 'Amélioration de la gestion du cache.',
),
),
'dependencies' =>
array (
'framework' =>
array (
'version' => '1.7.3',
'from' => '1.6.2',
),
),
),
7 =>
array (
'version' => '0.3.3',
'date' => '04/12/2019',
'description' => 'Améliorations divers',
'dependencies' =>
array (
'framework' =>
array (
'version' => '1.7.3',
),
),
),
8 =>
array (
'version' => '0.3.4',
'date' => '05/12/2019',
'description' => 'Améliorations divers',
'dependencies' =>
array (
'framework' =>
array (
'version' => '1.7.3',
),
),
),
9 =>
array (
'version' => '0.3.5',
'date' => '05/12/2019',
'description' => 'Améliorations divers',
'notes' =>
array (
0 =>
array (
'type' => 'FIX',
'text' => 'Correction du code de license utilisé avec Composer.',
),
),
'dependencies' =>
array (
'framework' =>
array (
'version' => '1.7.3',
),
),
),
10 =>
array (
'version' => '0.4.0',
'date' => '06/12/2019',
'description' => 'Améliorations divers',
'notes' =>
array (
0 =>
array (
'type' => 'ADD',
'code' => 'PGX-2664',
'text' => 'Configuration des logs détaillés.',
),
1 =>
array (
'type' => 'ADD',
'code' => 'PGX-2662',
'text' => 'Affichage de la version de curl.',
),
2 =>
array (
'type' => 'ADD',
'text' => 'Possibilité de configurer l\'activation des logs de debug.',
),
3 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'un diagnostique pour vérifier les droits du répertoire \'var\'.',
),
4 =>
array (
'type' => 'ADD',
'text' => 'Gestion des différents Shop et de leurs settings.',
),
5 =>
array (
'type' => 'ADD',
'text' => 'Internationalisation du frontoffice.',
),
6 =>
array (
'type' => 'ADD',
'text' => 'Ajout de traductions.',
),
7 =>
array (
'type' => 'ADD',
'text' => 'Unification du backoffice.',
),
8 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'une page de configuration du module dans le backoffice.',
),
9 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'une page de gestion du compte dans le backoffice.',
),
10 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'une page de gestion des boutons de paiements dans le backoffice.',
),
11 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'une page de gestion des montants éligibles dans le backoffice.',
),
12 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'un menu de navigation via configuration.',
),
13 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'une page d\'informations système dans le backoffice.',
),
14 =>
array (
'type' => 'ADD',
'text' => 'Unification du frontoffice.',
),
15 =>
array (
'type' => 'ADD',
'text' => 'Stylisation du backoffice.',
),
16 =>
array (
'type' => 'ADD',
'text' => 'Désactivation de la sécurité SSL en fonction de la configuration choisie par l\'utilisateur.',
),
17 =>
array (
'type' => 'ADD',
'text' => 'Possibilité de configurer le serveur du module directement depuis le backoffice.',
),
18 =>
array (
'type' => 'ADD',
'text' => 'Ajout de la gestion de la compensation carbone via Tree.',
),
19 =>
array (
'type' => 'ADD',
'text' => 'Amélioration du style du backoffice.',
),
20 =>
array (
'type' => 'ADD',
'text' => 'Affichage d\'une notification d\'erreur si un bouton est mal configuré.',
),
21 =>
array (
'type' => 'ADD',
'text' => 'Séparation du formulaire de configuration du module.',
),
22 =>
array (
'type' => 'ADD',
'text' => 'Affichage des erreurs de validation des boutons de paiements directement dans le backoffice, sous forme de notifications.',
),
23 =>
array (
'type' => 'ADD',
'text' => 'Amélioration du style du mode Insite.',
),
24 =>
array (
'type' => 'ADD',
'text' => 'Vert devient la couleur par défaut du logo dans le footer.',
),
25 =>
array (
'type' => 'ADD',
'text' => 'Ajout de nouvelles traductions.',
),
26 =>
array (
'type' => 'ADD',
'text' => 'Affichage d\'une notification dans le BO dans le cas où aucun Shop courant ne peut être déterminé.',
),
27 =>
array (
'type' => 'ADD',
'text' => 'Les fichiers de logs sont désormais stockés dans un sous-répertoire \'log\' et non plus directement dans le répertoire \'var\'.',
),
28 =>
array (
'type' => 'ADD',
'text' => 'Affichage des paths importants sur la page \'Informations système\' du backoffice.',
),
29 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'un selecteur de Shop dans le BO pour les CMS qui le requiert.',
),
30 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'une page pour gérer les traductions.',
),
31 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'un message de confirmation après la mise à jour d\'un bouton de paiement.',
),
32 =>
array (
'type' => 'ADD',
'text' => 'Utilisation de l\'anglais si on ne trouve pas une traduction.',
),
33 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'une preview de l\'image dans le formulaire de création d\'un bouton de paiement.',
),
34 =>
array (
'type' => 'ADD',
'text' => 'Affichage de la compensation carbone à la fin du processus de paiement.',
),
35 =>
array (
'type' => 'ADD',
'text' => 'Ajout de plusieurs pages permettant la connexion à un compte ClimateKit.',
),
36 =>
array (
'type' => 'ADD',
'text' => 'Mise à jour des images par défaut des boutons de paiements PayGreen.',
),
37 =>
array (
'type' => 'ADD',
'text' => 'Renommage du moyen de paiement Lunchr en Swile.',
),
38 =>
array (
'type' => 'ADD',
'text' => 'Affichage des boutons de paiements seulement si la devise sélectionée est supportée par PayGreen.',
),
39 =>
array (
'type' => 'ADD',
'text' => 'Affichage des release-notes dans le backoffice du module.',
),
40 =>
array (
'type' => 'ADD',
'text' => 'Gestion des incohérences dans les limitations de montant de panier d\'un bouton de paiement.',
),
41 =>
array (
'type' => 'ADD',
'text' => 'Affichage d\'un warning si un bouton de paiement en plusieurs fois est configuré avec plus de 4 versements.',
),
42 =>
array (
'type' => 'ADD',
'text' => 'Meilleur explication du caractère global des paramètres de configuration de la page \'Support\'.',
),
43 =>
array (
'type' => 'ADD',
'text' => 'Demande de confirmation avant l\'effacage d\'un fichier de log.',
),
44 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'une page permettant de configurer le filtrage de chaque bouton de paiement.',
),
45 =>
array (
'type' => 'ADD',
'text' => 'Possibilité de filtrer un bouton par catégorie de produit.',
),
46 =>
array (
'type' => 'ADD',
'code' => 'PGI-2585',
'text' => 'Ajout d\'explications détaillées sur la page de filtrage d\'un bouton de paiement.',
),
47 =>
array (
'type' => 'ADD',
'text' => 'Envoie des adresses client à l\'API de paiement.',
),
48 =>
array (
'type' => 'ADD',
'code' => 'PGI-2891',
'text' => 'Ajout du disclaimer statistiques sur la page d\'accueil.',
),
49 =>
array (
'type' => 'ADD',
'code' => 'PGI-2973',
'text' => 'Suppression de la page "statistiques" de l\'onglet ClimateKit.',
),
50 =>
array (
'type' => 'ADD',
'code' => 'PGI-2974',
'text' => 'Ajout d\'un lien vers le backoffice ClimateKit.',
),
51 =>
array (
'type' => 'ADD',
'code' => 'PGI-2849',
'text' => 'Renommer le ClimateKit en ClimateBot.',
),
52 =>
array (
'type' => 'ADD',
'code' => 'PGI-2852',
'text' => 'Ajout d\'un message warning sur le booléan d\'affichage de la page "en savoir plus".',
),
53 =>
array (
'type' => 'ADD',
'code' => 'PGI-2850',
'text' => 'Séparation des gestions des traductions pour le CarbonBot.',
),
54 =>
array (
'type' => 'ADD',
'code' => 'PGI-2913',
'text' => 'Refonte du design du carbon bot.',
),
55 =>
array (
'type' => 'ADD',
'code' => 'PGI-2873',
'text' => 'Ajout d\'un preview pour le CarbonBot.',
),
56 =>
array (
'type' => 'ADD',
'code' => 'PGI-2874',
'text' => 'Preview de la position et de la forme des angles du CarbonBot.',
),
57 =>
array (
'type' => 'ADD',
'code' => 'PGI-2875',
'text' => 'Preview de la couleur du CarbonBot.',
),
58 =>
array (
'type' => 'ADD',
'code' => 'PGI-2933',
'text' => 'Implémentation de l\'export du catalogue produit.',
),
59 =>
array (
'type' => 'ADD',
'code' => 'PGI-2876',
'text' => 'Preview de l\'affichage du lien "En savoir plus" dans le CarbonBot.',
),
60 =>
array (
'type' => 'ADD',
'code' => 'PGI-2882',
'text' => 'Ajout de l\'animation d\'ouverture du CarbonBot.',
),
61 =>
array (
'type' => 'ADD',
'code' => 'PGI-2967',
'text' => 'Ajout d\'un pour envoyer directement la liste des produits à l\'api Climate.',
),
62 =>
array (
'type' => 'ADD',
'code' => 'PGI-2995',
'text' => 'Griser les éléments du CarbonBot ayant une valeur à zéro.',
),
63 =>
array (
'type' => 'ADD',
'code' => 'PGI-3030',
'text' => 'Calculer l\'estimation carbone des produits du panier.',
),
64 =>
array (
'type' => 'ADD',
'code' => 'PGI-3043',
'text' => 'Intégration du design de la partie mobile du CarbonBot et possibilité de le cacher sur mobile.',
),
65 =>
array (
'type' => 'ADD',
'code' => 'PGI-3045',
'text' => 'Supprimer la possibilité de modifier le mode test.',
),
66 =>
array (
'type' => 'ADD',
'code' => 'PGI-3048',
'text' => 'Modifications de quelques textes en rapport avec le CarbonBot.',
),
67 =>
array (
'type' => 'ADD',
'code' => 'PGI-3087',
'text' => 'Déplacement du champs de configuration de l\'url vers la page "En savoir plus" dans le formulaire de configuration global.',
),
68 =>
array (
'type' => 'ADD',
'code' => 'PGI-3145',
'text' => 'Indication que l\'export du catalogue produit vers l\'api ClimateKit peut être très long.',
),
69 =>
array (
'type' => 'ADD',
'code' => 'PGI-3170',
'text' => 'Retirer le bouton d\'export de la liste des produits vers l\'api climatekit.',
),
70 =>
array (
'type' => 'ADD',
'code' => 'PGI-3216',
'text' => 'Rendre la server Bac à sable accessible depuis la version de production.',
),
71 =>
array (
'type' => 'ADD',
'code' => 'PGI-2523',
'text' => 'Baser l\'activation du mode Insite sur le paramètre général.',
),
72 =>
array (
'type' => 'ADD',
'code' => 'PGI-2524',
'text' => 'Retirer le champ "Interface de paiement" du formulaire des boutons.',
),
73 =>
array (
'type' => 'ADD',
'code' => 'PGI-2528',
'text' => 'Création d\'un template de ligne pour afficher un bouton de paiement de manière standardisée.',
),
74 =>
array (
'type' => 'ADD',
'code' => 'PGI-2529',
'text' => 'Afficher la liste des boutons sous forme de tableau.',
),
75 =>
array (
'type' => 'ADD',
'code' => 'PGI-2534',
'text' => 'Affichage d\'une documentation détaillée de chaque mode de paiement.',
),
76 =>
array (
'type' => 'ADD',
'code' => 'PGI-2531',
'text' => 'Permettre d\'ordonner les boutons par glissé/déposé.',
),
77 =>
array (
'type' => 'ADD',
'code' => 'PGI-3237',
'text' => 'Création du bloc de génération de l\'export produit.',
),
78 =>
array (
'type' => 'ADD',
'code' => 'PGI-3254',
'text' => 'Cacher le bouton d\'export si la clé \'carbon_footprint_catalogue\' est vide.',
),
79 =>
array (
'type' => 'ADD',
'code' => 'PGI-3320',
'text' => 'Ajouter un bouton de vidange du cache sur la page de support.',
),
80 =>
array (
'type' => 'ADD',
'code' => 'PGI-3338',
'text' => 'Ajouter la colonne \'ID-tech\' dans le CSV d\'export du catalogue produits.',
),
81 =>
array (
'type' => 'FIX',
'code' => 'PGI-943',
'text' => 'Passage des paiements XTIME à 4 versements maximum.',
),
82 =>
array (
'type' => 'FIX',
'code' => 'PGI-869',
'text' => 'Correction d\'une erreur lors de l\'installation de l\'image par défaut des boutons de paiements.',
),
83 =>
array (
'type' => 'FIX',
'code' => 'PGI-893',
'text' => 'Correction d\'un bug liés aux redirections curl.',
),
84 =>
array (
'type' => 'FIX',
'code' => 'PGI-524',
'text' => 'Correction d\'une erreur liée aux order states si les metadata n\'était pas définies.',
),
85 =>
array (
'type' => 'FIX',
'text' => 'Correction d\'une erreur lors de la génération de certains services du module.',
),
86 =>
array (
'type' => 'FIX',
'code' => 'PGI-946',
'text' => 'Correction d\'un bug de montant invalide lors de la configuration d\'un bouton de paiement.',
),
87 =>
array (
'type' => 'FIX',
'text' => 'Correction d\'une erreur lié au serveur du module.',
),
88 =>
array (
'type' => 'FIX',
'code' => 'PGI-1033',
'text' => 'Correction d\'une erreur de notification.',
),
89 =>
array (
'type' => 'FIX',
'text' => 'Correction mineure.',
),
90 =>
array (
'type' => 'FIX',
'code' => 'PGI-1046',
'text' => 'Correction d\'une erreur lors du premier paiement en mode RECURRING.',
),
91 =>
array (
'type' => 'FIX',
'code' => 'PGI-998',
'text' => 'Correction d\'une erreur liée à l\'utilisation de backslashes.',
),
92 =>
array (
'type' => 'FIX',
'code' => 'PGI-999',
'text' => 'Correction d\'une erreur liée à la connexion oAuth.',
),
93 =>
array (
'type' => 'FIX',
'text' => 'Corrections de traductions.',
),
94 =>
array (
'type' => 'FIX',
'text' => 'Corrections mineures.',
),
95 =>
array (
'type' => 'FIX',
'code' => 'PGI-1585',
'text' => 'Correction d\'une erreur de droits en écriture sur le serveur wordpress.com',
),
96 =>
array (
'type' => 'FIX',
'code' => 'PGI-1701',
'text' => 'Correction du Chmod utilisé lors du téléchargement d\'images.',
),
97 =>
array (
'type' => 'FIX',
'text' => 'Correction d\'un bug lié à l\'activation de Tree.',
),
98 =>
array (
'type' => 'FIX',
'text' => 'Correction css mineure.',
),
99 =>
array (
'type' => 'FIX',
'text' => 'Correction de traductions.',
),
100 =>
array (
'type' => 'FIX',
'text' => 'Mauvaises variables utilisées dans le service RequestBuilder.',
),
101 =>
array (
'type' => 'FIX',
'code' => 'PGI-1845',
'text' => 'Correction d\'un bug qui ne décochait pas tous les montants éligibles après configuration.',
),
102 =>
array (
'type' => 'FIX',
'code' => 'PGI-1744',
'text' => 'Correction de la gestion du timeout en cas d\'erreurs. Limitation des forwards responses à 3.',
),
103 =>
array (
'type' => 'FIX',
'code' => 'PGI-1898',
'text' => 'Redirection vers la page de détails de la commande en cas d\'order state inconnu.',
),
104 =>
array (
'type' => 'FIX',
'code' => 'PGI-1950',
'text' => 'Correction d\'un bug lié à une mauvaise gestion du cache Smarty.',
),
105 =>
array (
'type' => 'FIX',
'code' => 'PGI-1615',
'text' => 'Prise en compte des traductions enregistrées dans des sous-répertoires.',
),
106 =>
array (
'type' => 'FIX',
'code' => 'PGI-1777',
'text' => 'Correction d\'un bug avec les path absolus de l\'autoloader.',
),
107 =>
array (
'type' => 'FIX',
'code' => 'PGI-1965',
'text' => 'Correction d\'une erreur avec des commandes ayant le statut \'WAITING\'.',
),
108 =>
array (
'type' => 'FIX',
'text' => 'Correction d\'une erreur lors de l\'enregistrement d\'images via le module.',
),
109 =>
array (
'type' => 'FIX',
'text' => 'Correction d\'une erreur qui générait une nouvelle transaction malgré un PID déjà existant.',
),
110 =>
array (
'type' => 'FIX',
'text' => 'Correction d\'erreurs sur le composant ResourceBag.',
),
111 =>
array (
'type' => 'FIX',
'text' => 'Réutilisation de la constante DEFAULT_PICTURE pour assurer la compatibilité avec les anciennes versions.',
),
112 =>
array (
'type' => 'FIX',
'code' => 'PGI-2103',
'text' => 'Correction d\'une erreur qui empêchait l\'envoi des données Tree avec le navigateur Chrome.',
),
113 =>
array (
'type' => 'FIX',
'code' => 'PGI-1962',
'text' => 'Correction de la taille des logos PayGreen.',
),
114 =>
array (
'type' => 'FIX',
'code' => 'PGI-2322',
'text' => 'Correction des paiements suspects en cas de transaction composite.',
),
115 =>
array (
'type' => 'FIX',
'code' => 'PGI-2347',
'text' => 'Correction d\'un bug dans la gestion des montants éligibles en environnement multi-boutiques.',
),
116 =>
array (
'type' => 'FIX',
'code' => 'PGI-2385',
'text' => 'Correction d\'un bug lors de la confirmation des paiements TOKENIZE.',
),
117 =>
array (
'type' => 'FIX',
'code' => 'PGI-1609',
'text' => 'Affichage d\'une notification à l\'utilisateur en cas d\'erreurs lors de la connexion OAuth plutôt qu\'une exception.',
),
118 =>
array (
'type' => 'FIX',
'code' => 'PGI-2315',
'text' => 'Correction d\'un bug sur le formulaire de modification des boutons, un nouveau bouton était créé lors de la validation du formulaire.',
),
119 =>
array (
'type' => 'FIX',
'code' => 'PGI-2509',
'text' => 'Correction du lien permettant d\'afficher les release-notes au delà de 5.',
),
120 =>
array (
'type' => 'FIX',
'code' => 'PGI-2505',
'text' => 'Correction de l\'url du serveur ClimateKit de production.',
),
121 =>
array (
'type' => 'FIX',
'text' => 'Corrections divers sur la page d\'affichage des release-notes.',
),
122 =>
array (
'type' => 'FIX',
'text' => 'Utilisation systématique du LocalHandler pour récupérer la locale.',
),
123 =>
array (
'type' => 'FIX',
'code' => 'PGI-2433',
'text' => 'Vidange du cache lors de la modification des identifiants de l\'API de paiement.',
),
124 =>
array (
'type' => 'FIX',
'code' => 'PGI-2644',
'text' => 'Ajout des traductions manquantes sur la page de notification du frontoffice.',
),
125 =>
array (
'type' => 'FIX',
'code' => 'PGI-2634',
'text' => 'Ajout des traductions manquantes pour la configuration de la reconstitution manuelle du panier.',
),
126 =>
array (
'type' => 'FIX',
'code' => 'PGI-2643',
'text' => 'Correction du wrapper superglobal des sessions.',
),
127 =>
array (
'type' => 'FIX',
'code' => 'PGI-2670',
'text' => 'Correction de la taille du champ \'filtered_category_primaries\' de l\'entité Button.',
),
128 =>
array (
'type' => 'FIX',
'code' => 'PGI-2702',
'text' => 'Correction d\'un problème de typage lors de l\'enregistrement de l\'empreinte carbone.',
),
129 =>
array (
'type' => 'FIX',
'code' => 'PGI-2698',
'text' => 'Correction de la configuration de l\'entité Transaction causant un blocage des remboursements.',
),
130 =>
array (
'type' => 'FIX',
'code' => 'PGI-2688',
'text' => 'Correction des traductions manquantes en cas de paiement refusé.',
),
131 =>
array (
'type' => 'FIX',
'code' => 'PGI-2751',
'text' => 'Correction de la configuration de l\'url de retour utilisée en cas d\'erreur lors de la création d\'un paiement.',
),
132 =>
array (
'type' => 'FIX',
'code' => 'PGI-2729',
'text' => 'Complétion des entêtes envoyées par le Requester Fopen.',
),
133 =>
array (
'type' => 'FIX',
'code' => 'PGI-2761',
'text' => 'Correction du statut final utilisé pour cloturé un dossier de paiement ClimateKit.',
),
134 =>
array (
'type' => 'FIX',
'code' => 'PGI-2794',
'text' => 'Correction de la configuration des clients Curl.',
),
135 =>
array (
'type' => 'FIX',
'code' => 'PGI-2764',
'text' => 'Correction de la gestion de l\'orderId avec l\'API de paiement.',
),
136 =>
array (
'type' => 'FIX',
'code' => 'PGI-2836',
'text' => 'Correction d\'un problème d\'affichage des blocs de gestion des extensions et modification des classes css permettant de configurer la taille d\'un bloc.',
),
137 =>
array (
'type' => 'FIX',
'code' => 'PGI-2887',
'text' => 'Affichage des boutons de paiement lorsqu\'un produit n\'a pas de catégorie.',
),
138 =>
array (
'type' => 'FIX',
'code' => 'PGI-2896',
'text' => 'Sur la page d\'accueil, placer tout en bas le block indiquant que l\'on peut gérer ses extensions via la page dédiée.',
),
139 =>
array (
'type' => 'FIX',
'code' => 'PGI-2918',
'text' => 'Correction de la méthode de stockage des données de statistiques, utilisation de DataResource au lieu de cookie.',
),
140 =>
array (
'type' => 'FIX',
'code' => 'PGI-2934',
'text' => 'Correction de la gestion du hover sur le bouton d\'ouverture du carbon bot.',
),
141 =>
array (
'type' => 'FIX',
'code' => 'PGI-2971',
'text' => 'Refonte du css du CarbonBot pour être le plus indépendant possible du CMS et du thème utilisé.',
),
142 =>
array (
'type' => 'FIX',
'code' => 'PGI-3026',
'text' => 'Correction de l\'erreur 422 en cas de mise à jour d\'un impacte transport déjà défini.',
),
143 =>
array (
'type' => 'FIX',
'code' => 'PGI-3025',
'text' => 'Blocage de la page en cas d\'erreur sur le chargement du CarbonBot.',
),
144 =>
array (
'type' => 'FIX',
'code' => 'PGI-3003',
'text' => 'Problème de traduction sur la page de gestion du compte ClimateKit.',
),
145 =>
array (
'type' => 'FIX',
'code' => 'PGI-3044',
'text' => 'Gestion des erreurs lors du calcul du transport.',
),
146 =>
array (
'type' => 'FIX',
'code' => 'PGI-3054',
'text' => 'Définir la valeur par défaut pour les OutputBuilder non-conformes.',
),
147 =>
array (
'type' => 'FIX',
'code' => 'PGI-3050',
'text' => 'Correction d\'un problème du calcul de la compensation carbone à cause d\'erreurs liées à l\'interface Shopable.',
),
148 =>
array (
'type' => 'FIX',
'code' => 'PGI-3060',
'text' => 'Amélioration de la gestion des retours en cas de suppression d\'une collection.',
),
149 =>
array (
'type' => 'FIX',
'code' => 'PGI-3057',
'text' => 'Problème de IdFootprint introuvable.',
),
150 =>
array (
'type' => 'FIX',
'code' => 'PGI-3059',
'text' => 'Problème d\'affichage du bilan carbon global dans le CarbonBot.',
),
151 =>
array (
'type' => 'FIX',
'code' => 'PGI-3062',
'text' => 'Affichage de l\'empreinte carbone à la place de la compensation carbone sur la page de succès.',
),
152 =>
array (
'type' => 'FIX',
'code' => 'PGI-3096',
'text' => 'Correction des traductions du bilan carbone sur la page de succès.',
),
153 =>
array (
'type' => 'FIX',
'code' => 'PGI-3102',
'text' => 'Correction du mode de création d\'un dossier de compensation carbone.',
),
154 =>
array (
'type' => 'FIX',
'code' => 'PGI-3094',
'text' => 'Ouverture le lien \'en savoir plus\' du CarbonBot dans un nouvelle onglet.',
),
155 =>
array (
'type' => 'FIX',
'code' => 'PGI-3069',
'text' => 'Retouches CSS sur le CarbonBot.',
),
156 =>
array (
'type' => 'FIX',
'code' => 'PGI-3072',
'text' => 'Correction d\'un problème au niveau de l\'affichage du CarbonBot sur mobile.',
),
157 =>
array (
'type' => 'FIX',
'code' => 'PGI-3107',
'text' => 'Correction des boutons d\'export et de téléchargement du catalogue produite qui ne fonctionnent plus.',
),
158 =>
array (
'type' => 'FIX',
'code' => 'PGI-3108',
'text' => 'Correction de l\'affichage du formulaire de configuration générale de ClimateKit.',
),
159 =>
array (
'type' => 'FIX',
'code' => 'PGI-3105',
'text' => 'Sécuriser le listener FOTreeServicesListenersCarbonFootprintFinalization en cas d\'erreur.',
),
160 =>
array (
'type' => 'FIX',
'code' => 'PGI-3106',
'text' => 'Les champs "nom d\'utilisateur" et "compte" du formulaire de connexion ClimateKit sont inversés.',
),
161 =>
array (
'type' => 'FIX',
'code' => 'PGI-3164',
'text' => 'Correction d\'un problème de string non-quotté dans les fichiers CSV générés.',
),
162 =>
array (
'type' => 'FIX',
'code' => 'PGI-3228',
'text' => 'Correction de la traduction manquante si l\'export du catalogue produit échoue.',
),
163 =>
array (
'type' => 'FIX',
'code' => 'PGI-3283',
'text' => 'Chargement du fichier de style dédié au frontoffice.',
),
164 =>
array (
'type' => 'FIX',
'code' => 'PGI-3282',
'text' => 'Problème dans le template d\'affichage d\'une notification coté front.',
),
165 =>
array (
'type' => 'FIX',
'code' => 'PGI-3284',
'text' => 'Correction d\'un problème de drag and drop sous Chrome.',
),
166 =>
array (
'type' => 'FIX',
'code' => 'PGI-3250',
'text' => 'Correction du setting "tree_api_server" qui n\'est plus "global".',
),
167 =>
array (
'type' => 'FIX',
'code' => 'PGI-3323',
'text' => 'Problème de nommage des services de filtrage.',
),
168 =>
array (
'type' => 'FIX',
'code' => 'PGI-3339',
'text' => 'Correction du problème de cache de transaction sur les paiements en XTIME.',
),
169 =>
array (
'type' => 'PERF',
'text' => 'Optimisations mineures.',
),
170 =>
array (
'type' => 'PERF',
'text' => 'Amélioration du service Parser.',
),
171 =>
array (
'type' => 'PERF',
'text' => 'Optimisation globale du framework.',
),
172 =>
array (
'type' => 'PERF',
'text' => 'Factorisation des limitations de montant du panier.',
),
173 =>
array (
'type' => 'PERF',
'text' => 'Optimisation de l\'autoloader.',
),
174 =>
array (
'type' => 'PERF',
'text' => 'Amélioration de l\'enregistrement des settings.',
),
175 =>
array (
'type' => 'PERF',
'text' => 'Amélioration de la gestion des erreurs liées au mode Insite.',
),
176 =>
array (
'type' => 'PERF',
'text' => 'Meilleure gestion des upgrades.',
),
177 =>
array (
'type' => 'PERF',
'text' => 'Amélioration de la gestion des settings.',
),
178 =>
array (
'type' => 'PERF',
'text' => 'Amélioration de la résilience du système de cache du module et de Smarty.',
),
179 =>
array (
'type' => 'PERF',
'text' => 'Amélioration de la résilience de l\'autoloader.',
),
180 =>
array (
'type' => 'PERF',
'text' => 'Amélioration du cache Smarty.',
),
181 =>
array (
'type' => 'PERF',
'text' => 'Amélioration des logs sur la page de paiement.',
),
182 =>
array (
'type' => 'PERF',
'text' => 'On affiche plus la balise \'ul\' de notifications si il n\'y a pas de notifications.',
),
183 =>
array (
'type' => 'PERF',
'text' => 'Réduction du temps du PID Locking: 30sec -> 3sec.',
),
184 =>
array (
'type' => 'PERF',
'text' => 'Utilisation de la route /availablepaymenttype pour la récupération des moyens de paiement.',
),
185 =>
array (
'type' => 'PERF',
'text' => 'Utilisation de l\'index d\'autoloading pré-compilé.',
),
186 =>
array (
'type' => 'PERF',
'text' => 'Utilisation d\'un includer pré-compilé.',
),
187 =>
array (
'type' => 'PERF',
'code' => 'PGI-2989',
'text' => 'Renommer le concept d\'\'extension\' en \'produit\'.',
),
188 =>
array (
'type' => 'PERF',
'code' => 'PGI-2972',
'text' => 'Renommer le wording du plugin ClimateKit.',
),
189 =>
array (
'type' => 'PERF',
'code' => 'PGI-2975',
'text' => 'Modification de l\'affichage des crédentials de connexion au compte ClimateKit.',
),
),
'dependencies' =>
array (
'framework' =>
array (
'version' => '1.7.1',
'from' => '1.7.3',
),
),
),
11 =>
array (
'version' => '0.4.1',
'date' => '12/12/2019',
'description' => 'Améliorations divers',
'notes' =>
array (
0 =>
array (
'type' => 'FIX',
'text' => 'Correction du répertoire utilisé avec Composer.',
),
),
'dependencies' =>
array (
'framework' =>
array (
'version' => '1.7.1',
),
),
),
12 =>
array (
'version' => '1.0.0',
'date' => '13/07/2020',
'description' => 'Backoffice unifié et gestion du multi-shop.',
'notes' =>
array (
0 =>
array (
'type' => 'ADD',
'code' => 'PGI-1671',
'text' => 'Configuration des choix de mode d\'affichage pour les boutons de paiement.',
),
1 =>
array (
'type' => 'ADD',
'code' => 'PGI-1482',
'text' => 'Implementation du gestionnaire de boutique.',
),
2 =>
array (
'type' => 'ADD',
'text' => 'Possibilité de configurer l\'annulation d\'une transaction en cas de paiement refusé.',
),
3 =>
array (
'type' => 'ADD',
'text' => 'Possibilité de configurer l\'activation des logs de debug.',
),
4 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'un diagnostique pour vérifier les droits du répertoire \'var\'.',
),
5 =>
array (
'type' => 'ADD',
'text' => 'Gestion des différents Shop et de leurs settings.',
),
6 =>
array (
'type' => 'ADD',
'text' => 'Internationalisation du frontoffice.',
),
7 =>
array (
'type' => 'ADD',
'text' => 'Ajout de traductions.',
),
8 =>
array (
'type' => 'ADD',
'text' => 'Unification du backoffice.',
),
9 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'une page de configuration du module dans le backoffice.',
),
10 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'une page de gestion du compte dans le backoffice.',
),
11 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'une page de gestion des boutons de paiements dans le backoffice.',
),
12 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'une page de gestion des montants éligibles dans le backoffice.',
),
13 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'un menu de navigation via configuration.',
),
14 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'une page d\'informations système dans le backoffice.',
),
15 =>
array (
'type' => 'ADD',
'text' => 'Unification du frontoffice.',
),
16 =>
array (
'type' => 'ADD',
'text' => 'Stylisation du backoffice.',
),
17 =>
array (
'type' => 'ADD',
'text' => 'Désactivation de la sécurité SSL en fonction de la configuration choisie par l\'utilisateur.',
),
18 =>
array (
'type' => 'ADD',
'text' => 'Possibilité de configurer le serveur du module directement depuis le backoffice.',
),
19 =>
array (
'type' => 'ADD',
'text' => 'Ajout de la gestion de la compensation carbone via Tree.',
),
20 =>
array (
'type' => 'ADD',
'text' => 'Amélioration du style du backoffice.',
),
21 =>
array (
'type' => 'ADD',
'text' => 'Affichage d\'une notification d\'erreur si un bouton est mal configuré.',
),
22 =>
array (
'type' => 'ADD',
'text' => 'Séparation du formulaire de configuration du module.',
),
23 =>
array (
'type' => 'ADD',
'text' => 'Affichage des erreurs de validation des boutons de paiements directement dans le backoffice, sous forme de notifications.',
),
24 =>
array (
'type' => 'ADD',
'text' => 'Amélioration du style du mode Insite.',
),
25 =>
array (
'type' => 'ADD',
'text' => 'Vert devient la couleur par défaut du logo dans le footer.',
),
26 =>
array (
'type' => 'ADD',
'text' => 'Ajout de nouvelles traductions.',
),
27 =>
array (
'type' => 'ADD',
'text' => 'Affichage d\'une notification dans le BO dans le cas où aucun Shop courant ne peut être déterminé.',
),
28 =>
array (
'type' => 'ADD',
'text' => 'Les fichiers de logs sont désormais stockés dans un sous-répertoire \'log\' et non plus directement dans le répertoire \'var\'.',
),
29 =>
array (
'type' => 'ADD',
'text' => 'Affichage des paths importants sur la page \'Informations système\' du backoffice.',
),
30 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'un selecteur de Shop dans le BO pour les CMS qui le requiert.',
),
31 =>
array (
'type' => 'FIX',
'code' => 'PGI-1674',
'text' => 'Corrections des restrictions de panier.',
),
32 =>
array (
'type' => 'FIX',
'code' => 'PGI-1497',
'text' => 'Corrections des templates Magento.',
),
33 =>
array (
'type' => 'FIX',
'code' => 'PGI-1573',
'text' => 'Correction du remboursement.',
),
34 =>
array (
'type' => 'FIX',
'code' => 'PGI-879',
'text' => 'Correction d\'un message d\'erreur indiquant que le paiement RECURRING ne pouvait pas être décalé alors que cela concerné le paiement XTIME.',
),
35 =>
array (
'type' => 'FIX',
'code' => 'PGI-883',
'text' => 'Correction d\'un bug de corruption du fichier autoload.cache.php.',
),
36 =>
array (
'type' => 'FIX',
'code' => 'PGI-903',
'text' => 'Correction d\'un bug au niveau du PID ranking lié au code legacy de l\'API PayGreen.',
),
37 =>
array (
'type' => 'FIX',
'code' => 'PGI-943',
'text' => 'Passage des paiements XTIME à 4 versements maximum.',
),
38 =>
array (
'type' => 'FIX',
'code' => 'PGI-869',
'text' => 'Correction d\'une erreur lors de l\'installation de l\'image par défaut des boutons de paiements.',
),
39 =>
array (
'type' => 'FIX',
'code' => 'PGI-893',
'text' => 'Correction d\'un bug liés aux redirections curl.',
),
40 =>
array (
'type' => 'FIX',
'code' => 'PGI-524',
'text' => 'Correction d\'une erreur liée aux order states si les metadata n\'était pas définies.',
),
41 =>
array (
'type' => 'FIX',
'text' => 'Correction d\'une erreur lors de la génération de certains services du module.',
),
42 =>
array (
'type' => 'FIX',
'code' => 'PGI-946',
'text' => 'Correction d\'un bug de montant invalide lors de la configuration d\'un bouton de paiement.',
),
43 =>
array (
'type' => 'FIX',
'text' => 'Correction d\'une erreur lié au serveur du module.',
),
44 =>
array (
'type' => 'FIX',
'code' => 'PGI-1033',
'text' => 'Correction d\'une erreur de notification.',
),
45 =>
array (
'type' => 'FIX',
'text' => 'Correction mineure.',
),
46 =>
array (
'type' => 'FIX',
'code' => 'PGI-1046',
'text' => 'Correction d\'une erreur lors du premier paiement en mode RECURRING.',
),
47 =>
array (
'type' => 'FIX',
'code' => 'PGI-998',
'text' => 'Correction d\'une erreur liée à l\'utilisation de backslashes.',
),
48 =>
array (
'type' => 'FIX',
'code' => 'PGI-999',
'text' => 'Correction d\'une erreur liée à la connexion oAuth.',
),
49 =>
array (
'type' => 'FIX',
'text' => 'Corrections de traductions.',
),
50 =>
array (
'type' => 'FIX',
'text' => 'Corrections mineures.',
),
51 =>
array (
'type' => 'FIX',
'code' => 'PGI-1585',
'text' => 'Correction d\'une erreur de droits en écriture sur le serveur wordpress.com',
),
52 =>
array (
'type' => 'FIX',
'code' => 'PGI-1701',
'text' => 'Correction du Chmod utilisé lors du téléchargement d\'images.',
),
53 =>
array (
'type' => 'FIX',
'text' => 'Correction d\'un bug lié à l\'activation de Tree.',
),
54 =>
array (
'type' => 'FIX',
'text' => 'Correction css mineure.',
),
55 =>
array (
'type' => 'PERF',
'text' => 'Optimisations mineures.',
),
56 =>
array (
'type' => 'PERF',
'text' => 'Amélioration du service Parser.',
),
57 =>
array (
'type' => 'PERF',
'text' => 'Optimisation globale du framework.',
),
58 =>
array (
'type' => 'PERF',
'text' => 'Factorisation des limitations de montant du panier.',
),
59 =>
array (
'type' => 'PERF',
'text' => 'Optimisation de l\'autoloader.',
),
60 =>
array (
'type' => 'PERF',
'text' => 'Amélioration de l\'enregistrement des settings.',
),
61 =>
array (
'type' => 'PERF',
'text' => 'Amélioration de la gestion des erreurs liées au mode Insite.',
),
62 =>
array (
'type' => 'PERF',
'text' => 'Meilleure gestion des upgrades.',
),
63 =>
array (
'type' => 'PERF',
'text' => 'Amélioration de la gestion des settings.',
),
64 =>
array (
'type' => 'PERF',
'text' => 'Amélioration de la résilience du système de cache du module et de Smarty.',
),
65 =>
array (
'type' => 'PERF',
'text' => 'Amélioration de la résilience de l\'autoloader.',
),
),
'dependencies' =>
array (
'framework' =>
array (
'version' => '2.6.0',
'from' => '1.7.1',
),
),
),
13 =>
array (
'version' => '1.0.1',
'date' => '28/08/2020',
'description' => 'Améliorations divers',
'notes' =>
array (
0 =>
array (
'type' => 'FIX',
'text' => 'Correction de traductions.',
),
),
'dependencies' =>
array (
'framework' =>
array (
'version' => '2.6.1',
'from' => '2.6.0',
),
),
),
14 =>
array (
'version' => '1.1.0',
'date' => '15/09/2020',
'description' => 'Traduction intégrale du front-office.',
'notes' =>
array (
0 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'une page pour gérer les traductions.',
),
1 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'un message de confirmation après la mise à jour d\'un bouton de paiement.',
),
2 =>
array (
'type' => 'FIX',
'code' => 'PGI-1429',
'text' => 'Amélioration des traductions par défaut.',
),
3 =>
array (
'type' => 'FIX',
'text' => 'Mauvaises variables utilisées dans le service RequestBuilder.',
),
4 =>
array (
'type' => 'FIX',
'code' => 'PGI-1898',
'text' => 'Redirection vers la page de détails de la commande en cas d\'order state inconnu.',
),
5 =>
array (
'type' => 'FIX',
'code' => 'PGI-1845',
'text' => 'Correction d\'un bug qui ne décochait pas tous les montants éligibles après configuration.',
),
),
'dependencies' =>
array (
'framework' =>
array (
'version' => '2.7.0',
'from' => '2.6.1',
),
),
),
15 =>
array (
'version' => '1.1.1',
'date' => '22/09/2020',
'description' => 'Améliorations divers',
'notes' =>
array (
0 =>
array (
'type' => 'ADD',
'text' => 'Utilisation de l\'anglais si on ne trouve pas une traduction.',
),
1 =>
array (
'type' => 'FIX',
'code' => 'PGI-1744',
'text' => 'Correction de la gestion du timeout en cas d\'erreurs. Limitation des forwards responses à 3.',
),
2 =>
array (
'type' => 'FIX',
'code' => 'PGI-1898',
'text' => 'Redirection vers la page de détails de la commande en cas d\'order state inconnu.',
),
3 =>
array (
'type' => 'PERF',
'text' => 'Amélioration du cache Smarty.',
),
),
'dependencies' =>
array (
'framework' =>
array (
'version' => '2.7.3',
'from' => '2.7.0',
),
),
),
16 =>
array (
'version' => '1.2.0',
'date' => '08/10/2020',
'description' => 'Utilisation d\'images dédiées à chaque moyen de paiement.',
'notes' =>
array (
0 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'une preview de l\'image dans le formulaire de création d\'un bouton de paiement.',
),
1 =>
array (
'type' => 'FIX',
'code' => 'PGI-1950',
'text' => 'Correction d\'un bug lié à une mauvaise gestion du cache Smarty.',
),
2 =>
array (
'type' => 'FIX',
'code' => 'PGI-1615',
'text' => 'Prise en compte des traductions enregistrées dans des sous-répertoires.',
),
3 =>
array (
'type' => 'FIX',
'code' => 'PGI-1777',
'text' => 'Correction d\'un bug avec les path absolus de l\'autoloader.',
),
4 =>
array (
'type' => 'FIX',
'code' => 'PGI-1965',
'text' => 'Correction d\'une erreur avec des commandes ayant le statut \'WAITING\'.',
),
5 =>
array (
'type' => 'FIX',
'text' => 'Correction d\'une erreur lors de l\'enregistrement d\'images via le module.',
),
6 =>
array (
'type' => 'FIX',
'text' => 'Correction d\'une erreur qui générait une nouvelle transaction malgré un PID déjà existant.',
),
7 =>
array (
'type' => 'FIX',
'text' => 'Correction d\'erreurs sur le composant ResourceBag.',
),
8 =>
array (
'type' => 'PERF',
'text' => 'Amélioration des logs sur la page de paiement.',
),
9 =>
array (
'type' => 'PERF',
'text' => 'On affiche plus la balise \'ul\' de notifications si il n\'y a pas de notifications.',
),
10 =>
array (
'type' => 'PERF',
'text' => 'Réduction du temps du PID Locking: 30sec -> 3sec.',
),
),
'dependencies' =>
array (
'framework' =>
array (
'version' => '2.8.1',
'from' => '2.7.3',
),
),
),
17 =>
array (
'version' => '1.2.1',
'date' => '04/02/2021',
'description' => 'Améliorations divers',
'notes' =>
array (
0 =>
array (
'type' => 'FIX',
'text' => 'Réutilisation de la constante DEFAULT_PICTURE pour assurer la compatibilité avec les anciennes versions.',
),
1 =>
array (
'type' => 'FIX',
'code' => 'PGI-2103',
'text' => 'Correction d\'une erreur qui empêchait l\'envoi des données Tree avec le navigateur Chrome.',
),
2 =>
array (
'type' => 'FIX',
'code' => 'PGI-1962',
'text' => 'Correction de la taille des logos PayGreen.',
),
3 =>
array (
'type' => 'FIX',
'code' => 'PGI-2322',
'text' => 'Correction des retours clients après payment TRD non-basés sur un PaymentRecord.',
),
4 =>
array (
'type' => 'PERF',
'code' => 'PGI-1792',
'text' => 'Optimisation du chargement du script tree.js',
),
),
'dependencies' =>
array (
'framework' =>
array (
'version' => '2.9.1',
'from' => '2.8.1',
),
),
),
18 =>
array (
'version' => '1.2.2',
'date' => '04/02/2021',
'description' => 'Améliorations divers',
'dependencies' =>
array (
'framework' =>
array (
'version' => '2.9.2',
'from' => '2.9.1',
),
),
),
19 =>
array (
'version' => '1.2.3',
'date' => '18/02/2021',
'description' => 'Améliorations divers',
'notes' =>
array (
0 =>
array (
'type' => 'FIX',
'code' => 'PGI-2434',
'text' => 'Correction de l\'initialisation avec Composer en cas de liens symboliques.',
),
),
'dependencies' =>
array (
'framework' =>
array (
'version' => '2.9.3',
'from' => '2.9.2',
),
),
),
20 =>
array (
'version' => '1.2.4',
'date' => '22/02/2021',
'description' => 'Améliorations divers',
'notes' =>
array (
0 =>
array (
'type' => 'FIX',
'code' => 'PGI-2462',
'text' => 'Corrections des problèmes de cache du multi-shop.',
),
),
'dependencies' =>
array (
'framework' =>
array (
'version' => '2.9.3',
),
),
),
21 =>
array (
'version' => '1.2.5',
'date' => '11/03/2021',
'description' => 'Améliorations divers',
'dependencies' =>
array (
'framework' =>
array (
'version' => '2.9.3',
),
),
),
22 =>
array (
'version' => '2.0.0',
'date' => '26/04/2021',
'description' => 'Nouveau builder, release-notes et filtrage des boutons de paiement.',
'notes' =>
array (
0 =>
array (
'type' => 'ADD',
'code' => 'PGI-2450',
'text' => 'Rédaction des release-notes du module.',
),
1 =>
array (
'type' => 'ADD',
'code' => 'PGI-2639',
'text' => 'Envoyer un evènement à magento quand un paiment est effectué.',
),
2 =>
array (
'type' => 'ADD',
'code' => 'PGI-1695',
'text' => 'Configuration du retour client lors d\'un paiement refusé.',
),
3 =>
array (
'type' => 'ADD',
'code' => 'PGI-191',
'text' => 'Configuration du retour client lors d\'un paiement abandonné.',
),
4 =>
array (
'type' => 'ADD',
'code' => 'PGI-1696',
'text' => 'Implementation de la reconstruction du panier.',
),
5 =>
array (
'type' => 'ADD',
'text' => 'Affichage de la compensation carbone à la fin du processus de paiement.',
),
6 =>
array (
'type' => 'ADD',
'text' => 'Ajout de plusieurs pages permettant la connexion à un compte ClimateKit.',
),
7 =>
array (
'type' => 'ADD',
'text' => 'Mise à jour des images par défaut des boutons de paiements PayGreen.',
),
8 =>
array (
'type' => 'ADD',
'text' => 'Renommage du moyen de paiement Lunchr en Swile.',
),
9 =>
array (
'type' => 'ADD',
'text' => 'Affichage des boutons de paiements seulement si la devise sélectionée est supportée par PayGreen.',
),
10 =>
array (
'type' => 'ADD',
'text' => 'Affichage des release-notes dans le backoffice du module.',
),
11 =>
array (
'type' => 'ADD',
'text' => 'Gestion des incohérences dans les limitations de montant de panier d\'un bouton de paiement.',
),
12 =>
array (
'type' => 'ADD',
'text' => 'Affichage d\'un warning si un bouton de paiement en plusieurs fois est configuré avec plus de 4 versements.',
),
13 =>
array (
'type' => 'ADD',
'text' => 'Meilleur explication du caractère global des paramètres de configuration de la page \'Support\'.',
),
14 =>
array (
'type' => 'ADD',
'text' => 'Demande de confirmation avant l\'effacage d\'un fichier de log.',
),
15 =>
array (
'type' => 'ADD',
'text' => 'Ajout d\'une page permettant de configurer le filtrage de chaque bouton de paiement.',
),
16 =>
array (
'type' => 'ADD',
'text' => 'Possibilité de filtrer un bouton par catégorie de produit.',
),
17 =>
array (
'type' => 'ADD',
'code' => 'PGI-2585',
'text' => 'Ajout d\'explications détaillées sur la page de filtrage d\'un bouton de paiement.',
),
18 =>
array (
'type' => 'ADD',
'text' => 'Envoie des adresses client à l\'API de paiement.',
),
19 =>
array (
'type' => 'FIX',
'code' => 'PGI-2635',
'text' => 'Message incohérent si la reconstruction du panier est désactivée.',
),
20 =>
array (
'type' => 'FIX',
'code' => 'PGI-2322',
'text' => 'Correction des paiements suspects en cas de transaction composite.',
),
21 =>
array (
'type' => 'FIX',
'code' => 'PGI-2347',
'text' => 'Correction d\'un bug dans la gestion des montants éligibles en environnement multi-boutiques.',
),
22 =>
array (
'type' => 'FIX',
'code' => 'PGI-2385',
'text' => 'Correction d\'un bug lors de la confirmation des paiements TOKENIZE.',
),
23 =>
array (
'type' => 'FIX',
'code' => 'PGI-1609',
'text' => 'Affichage d\'une notification à l\'utilisateur en cas d\'erreurs lors de la connexion OAuth plutôt qu\'une exception.',
),
24 =>
array (
'type' => 'FIX',
'code' => 'PGI-2315',
'text' => 'Correction d\'un bug sur le formulaire de modification des boutons, un nouveau bouton était créé lors de la validation du formulaire.',
),
25 =>
array (
'type' => 'FIX',
'code' => 'PGI-2509',
'text' => 'Correction du lien permettant d\'afficher les release-notes au delà de 5.',
),
26 =>
array (
'type' => 'FIX',
'code' => 'PGI-2505',
'text' => 'Correction de l\'url du serveur ClimateKit de production.',
),
27 =>
array (
'type' => 'FIX',
'text' => 'Corrections divers sur la page d\'affichage des release-notes.',
),
28 =>
array (
'type' => 'FIX',
'text' => 'Utilisation systématique du LocalHandler pour récupérer la locale.',
),
29 =>
array (
'type' => 'FIX',
'code' => 'PGI-2433',
'text' => 'Vidange du cache lors de la modification des identifiants de l\'API de paiement.',
),
30 =>
array (
'type' => 'FIX',
'code' => 'PGI-2644',
'text' => 'Ajout des traductions manquantes sur la page de notification du frontoffice.',
),
31 =>
array (
'type' => 'FIX',
'code' => 'PGI-2634',
'text' => 'Ajout des traductions manquantes pour la configuration de la reconstitution manuelle du panier.',
),
32 =>
array (
'type' => 'FIX',
'code' => 'PGI-2643',
'text' => 'Correction du wrapper superglobal des sessions.',
),
33 =>
array (
'type' => 'FIX',
'code' => 'PGI-2670',
'text' => 'Correction de la taille du champ \'filtered_category_primaries\' de l\'entité Button.',
),
34 =>
array (
'type' => 'FIX',
'code' => 'PGI-2702',
'text' => 'Correction d\'un problème de typage lors de l\'enregistrement de l\'empreinte carbone.',
),
35 =>
array (
'type' => 'FIX',
'code' => 'PGI-2698',
'text' => 'Correction de la configuration de l\'entité Transaction causant un blocage des remboursements.',
),
36 =>
array (
'type' => 'FIX',
'code' => 'PGI-2688',
'text' => 'Correction des traductions manquantes en cas de paiement refusé.',
),
37 =>
array (
'type' => 'PERF',
'code' => 'PGI-1690',
'text' => 'Simplification de l\'affichage des boutons de paiement avec un seul bouton.',
),
38 =>
array (
'type' => 'PERF',
'text' => 'Utilisation de la route /availablepaymenttype pour la récupération des moyens de paiement.',
),
39 =>
array (
'type' => 'PERF',
'text' => 'Utilisation de l\'index d\'autoloading pré-compilé.',
),
40 =>
array (
'type' => 'PERF',
'text' => 'Utilisation d\'un includer pré-compilé.',
),
),
'dependencies' =>
array (
'framework' =>
array (
'version' => '3.2.6',
'from' => '2.9.3',
),
),
),
23 =>
array (
'version' => '2.0.1',
'date' => '29/04/2021',
'description' => 'Améliorations divers',
'notes' =>
array (
0 =>
array (
'type' => 'FIX',
'code' => 'PGI-2751',
'text' => 'Correction de la configuration de l\'url de retour utilisée en cas d\'erreur lors de la création d\'un paiement.',
),
1 =>
array (
'type' => 'FIX',
'code' => 'PGI-2729',
'text' => 'Complétion des entêtes envoyées par le Requester Fopen.',
),
2 =>
array (
'type' => 'FIX',
'code' => 'PGI-2761',
'text' => 'Correction du statut final utilisé pour cloturé un dossier de paiement ClimateKit.',
),
),
'dependencies' =>
array (
'framework' =>
array (
'version' => '3.2.7',
'from' => '3.2.6',
),
),
),
24 =>
array (
'version' => '2.0.2',
'date' => '05/05/2021',
'description' => 'Améliorations divers',
'notes' =>
array (
0 =>
array (
'type' => 'FIX',
'code' => 'PGI-2794',
'text' => 'Correction de la configuration des clients Curl.',
),
1 =>
array (
'type' => 'FIX',
'code' => 'PGI-2764',
'text' => 'Correction de la gestion de l\'orderId avec l\'API de paiement.',
),
),
'dependencies' =>
array (
'framework' =>
array (
'version' => '3.2.8',
'from' => '3.2.7',
),
),
),
25 =>
array (
'version' => '2.1.0',
'date' => '29/06/2021',
'description' => 'Améliorations divers',
'notes' =>
array (
0 =>
array (
'type' => 'ADD',
'code' => 'PGI-2891',
'text' => 'Ajout du disclaimer statistiques sur la page d\'accueil.',
),
1 =>
array (
'type' => 'ADD',
'code' => 'PGI-2973',
'text' => 'Suppression de la page "statistiques" de l\'onglet ClimateKit.',
),
2 =>
array (
'type' => 'ADD',
'code' => 'PGI-2974',
'text' => 'Ajout d\'un lien vers le backoffice ClimateKit.',
),
3 =>
array (
'type' => 'ADD',
'code' => 'PGI-2849',
'text' => 'Renommer le ClimateKit en ClimateBot.',
),
4 =>
array (
'type' => 'ADD',
'code' => 'PGI-2852',
'text' => 'Ajout d\'un message warning sur le booléan d\'affichage de la page "en savoir plus".',
),
5 =>
array (
'type' => 'ADD',
'code' => 'PGI-2850',
'text' => 'Séparation des gestions des traductions pour le CarbonBot.',
),
6 =>
array (
'type' => 'ADD',
'code' => 'PGI-2913',
'text' => 'Refonte du design du carbon bot.',
),
7 =>
array (
'type' => 'ADD',
'code' => 'PGI-2873',
'text' => 'Ajout d\'un preview pour le CarbonBot.',
),
8 =>
array (
'type' => 'ADD',
'code' => 'PGI-2874',
'text' => 'Preview de la position et de la forme des angles du CarbonBot.',
),
9 =>
array (
'type' => 'ADD',
'code' => 'PGI-2875',
'text' => 'Preview de la couleur du CarbonBot.',
),
10 =>
array (
'type' => 'ADD',
'code' => 'PGI-2933',
'text' => 'Implémentation de l\'export du catalogue produit.',
),
11 =>
array (
'type' => 'ADD',
'code' => 'PGI-2876',
'text' => 'Preview de l\'affichage du lien "En savoir plus" dans le CarbonBot.',
),
12 =>
array (
'type' => 'ADD',
'code' => 'PGI-2882',
'text' => 'Ajout de l\'animation d\'ouverture du CarbonBot.',
),
13 =>
array (
'type' => 'ADD',
'code' => 'PGI-2967',
'text' => 'Ajout d\'un pour envoyer directement la liste des produits à l\'api Climate.',
),
14 =>
array (
'type' => 'ADD',
'code' => 'PGI-2995',
'text' => 'Griser les éléments du CarbonBot ayant une valeur à zéro.',
),
15 =>
array (
'type' => 'ADD',
'code' => 'PGI-3030',
'text' => 'Calculer l\'estimation carbone des produits du panier.',
),
16 =>
array (
'type' => 'ADD',
'code' => 'PGI-3043',
'text' => 'Intégration du design de la partie mobile du CarbonBot et possibilité de le cacher sur mobile.',
),
17 =>
array (
'type' => 'ADD',
'code' => 'PGI-3045',
'text' => 'Supprimer la possibilité de modifier le mode test.',
),
18 =>
array (
'type' => 'ADD',
'code' => 'PGI-3048',
'text' => 'Modifications de quelques textes en rapport avec le CarbonBot.',
),
19 =>
array (
'type' => 'ADD',
'code' => 'PGI-3087',
'text' => 'Déplacement du champs de configuration de l\'url vers la page "En savoir plus" dans le formulaire de configuration global.',
),
20 =>
array (
'type' => 'ADD',
'code' => 'PGI-3145',
'text' => 'Indication que l\'export du catalogue produit vers l\'api ClimateKit peut être très long.',
),
21 =>
array (
'type' => 'ADD',
'code' => 'PGI-3170',
'text' => 'Retirer le bouton d\'export de la liste des produits vers l\'api climatekit.',
),
22 =>
array (
'type' => 'FIX',
'code' => 'PGI-3099',
'text' => 'Utiliser la bonne clé de traduction dans le template \'carbon-offset-merchant\'.',
),
23 =>
array (
'type' => 'FIX',
'code' => 'PGI-2836',
'text' => 'Correction d\'un problème d\'affichage des blocs de gestion des extensions et modification des classes css permettant de configurer la taille d\'un bloc.',
),
24 =>
array (
'type' => 'FIX',
'code' => 'PGI-2887',
'text' => 'Affichage des boutons de paiement lorsqu\'un produit n\'a pas de catégorie.',
),
25 =>
array (
'type' => 'FIX',
'code' => 'PGI-2896',
'text' => 'Sur la page d\'accueil, placer tout en bas le block indiquant que l\'on peut gérer ses extensions via la page dédiée.',
),
26 =>
array (
'type' => 'FIX',
'code' => 'PGI-2918',
'text' => 'Correction de la méthode de stockage des données de statistiques, utilisation de DataResource au lieu de cookie.',
),
27 =>
array (
'type' => 'FIX',
'code' => 'PGI-2934',
'text' => 'Correction de la gestion du hover sur le bouton d\'ouverture du carbon bot.',
),
28 =>
array (
'type' => 'FIX',
'code' => 'PGI-2971',
'text' => 'Refonte du css du CarbonBot pour être le plus indépendant possible du CMS et du thème utilisé.',
),
29 =>
array (
'type' => 'FIX',
'code' => 'PGI-3026',
'text' => 'Correction de l\'erreur 422 en cas de mise à jour d\'un impacte transport déjà défini.',
),
30 =>
array (
'type' => 'FIX',
'code' => 'PGI-3025',
'text' => 'Blocage de la page en cas d\'erreur sur le chargement du CarbonBot.',
),
31 =>
array (
'type' => 'FIX',
'code' => 'PGI-3003',
'text' => 'Problème de traduction sur la page de gestion du compte ClimateKit.',
),
32 =>
array (
'type' => 'FIX',
'code' => 'PGI-3044',
'text' => 'Gestion des erreurs lors du calcul du transport.',
),
33 =>
array (
'type' => 'FIX',
'code' => 'PGI-3054',
'text' => 'Définir la valeur par défaut pour les OutputBuilder non-conformes.',
),
34 =>
array (
'type' => 'FIX',
'code' => 'PGI-3050',
'text' => 'Correction d\'un problème du calcul de la compensation carbone à cause d\'erreurs liées à l\'interface Shopable.',
),
35 =>
array (
'type' => 'FIX',
'code' => 'PGI-3060',
'text' => 'Amélioration de la gestion des retours en cas de suppression d\'une collection.',
),
36 =>
array (
'type' => 'FIX',
'code' => 'PGI-3057',
'text' => 'Problème de IdFootprint introuvable.',
),
37 =>
array (
'type' => 'FIX',
'code' => 'PGI-3059',
'text' => 'Problème d\'affichage du bilan carbon global dans le CarbonBot.',
),
38 =>
array (
'type' => 'FIX',
'code' => 'PGI-3062',
'text' => 'Affichage de l\'empreinte carbone à la place de la compensation carbone sur la page de succès.',
),
39 =>
array (
'type' => 'FIX',
'code' => 'PGI-3096',
'text' => 'Correction des traductions du bilan carbone sur la page de succès.',
),
40 =>
array (
'type' => 'FIX',
'code' => 'PGI-3102',
'text' => 'Correction du mode de création d\'un dossier de compensation carbone.',
),
41 =>
array (
'type' => 'FIX',
'code' => 'PGI-3094',
'text' => 'Ouverture le lien \'en savoir plus\' du CarbonBot dans un nouvelle onglet.',
),
42 =>
array (
'type' => 'FIX',
'code' => 'PGI-3069',
'text' => 'Retouches CSS sur le CarbonBot.',
),
43 =>
array (
'type' => 'FIX',
'code' => 'PGI-3072',
'text' => 'Correction d\'un problème au niveau de l\'affichage du CarbonBot sur mobile.',
),
44 =>
array (
'type' => 'FIX',
'code' => 'PGI-3107',
'text' => 'Correction des boutons d\'export et de téléchargement du catalogue produite qui ne fonctionnent plus.',
),
45 =>
array (
'type' => 'FIX',
'code' => 'PGI-3108',
'text' => 'Correction de l\'affichage du formulaire de configuration générale de ClimateKit.',
),
46 =>
array (
'type' => 'FIX',
'code' => 'PGI-3105',
'text' => 'Sécuriser le listener FOTreeServicesListenersCarbonFootprintFinalization en cas d\'erreur.',
),
47 =>
array (
'type' => 'FIX',
'code' => 'PGI-3106',
'text' => 'Les champs "nom d\'utilisateur" et "compte" du formulaire de connexion ClimateKit sont inversés.',
),
48 =>
array (
'type' => 'FIX',
'code' => 'PGI-3164',
'text' => 'Correction d\'un problème de string non-quotté dans les fichiers CSV générés.',
),
49 =>
array (
'type' => 'PERF',
'code' => 'PGI-2989',
'text' => 'Renommer le concept d\'\'extension\' en \'produit\'.',
),
50 =>
array (
'type' => 'PERF',
'code' => 'PGI-2972',
'text' => 'Renommer le wording du plugin ClimateKit.',
),
51 =>
array (
'type' => 'PERF',
'code' => 'PGI-2975',
'text' => 'Modification de l\'affichage des crédentials de connexion au compte ClimateKit.',
),
),
'dependencies' =>
array (
'framework' =>
array (
'version' => '3.4.4',
'from' => '3.2.8',
),
),
),
26 =>
array (
'version' => '2.1.1',
'date' => '13/07/2021',
'description' => 'Améliorations divers',
'notes' =>
array (
0 =>
array (
'type' => 'ADD',
'code' => 'PGI-3216',
'text' => 'Rendre la server Bac à sable accessible depuis la version de production.',
),
),
'dependencies' =>
array (
'framework' =>
array (
'version' => '3.4.5',
'from' => '3.4.4',
),
),
),
27 =>
array (
'version' => '2.2.0',
'date' => '23/07/2021',
'description' => 'Améliorations divers',
'notes' =>
array (
0 =>
array (
'type' => 'ADD',
'code' => 'PGI-2523',
'text' => 'Baser l\'activation du mode Insite sur le paramètre général.',
),
1 =>
array (
'type' => 'ADD',
'code' => 'PGI-2524',
'text' => 'Retirer le champ "Interface de paiement" du formulaire des boutons.',
),
2 =>
array (
'type' => 'ADD',
'code' => 'PGI-2528',
'text' => 'Création d\'un template de ligne pour afficher un bouton de paiement de manière standardisée.',
),
3 =>
array (
'type' => 'ADD',
'code' => 'PGI-2529',
'text' => 'Afficher la liste des boutons sous forme de tableau.',
),
4 =>
array (
'type' => 'ADD',
'code' => 'PGI-2534',
'text' => 'Affichage d\'une documentation détaillée de chaque mode de paiement.',
),
5 =>
array (
'type' => 'ADD',
'code' => 'PGI-2531',
'text' => 'Permettre d\'ordonner les boutons par glissé/déposé.',
),
6 =>
array (
'type' => 'FIX',
'code' => 'PGI-3318',
'text' => 'Désactivation de la protection CSRF pour les requêtes ajax.',
),
7 =>
array (
'type' => 'FIX',
'code' => 'PGI-3228',
'text' => 'Correction de la traduction manquante si l\'export du catalogue produit échoue.',
),
8 =>
array (
'type' => 'FIX',
'code' => 'PGI-3283',
'text' => 'Chargement du fichier de style dédié au frontoffice.',
),
9 =>
array (
'type' => 'FIX',
'code' => 'PGI-3282',
'text' => 'Problème dans le template d\'affichage d\'une notification coté front.',
),
10 =>
array (
'type' => 'FIX',
'code' => 'PGI-3284',
'text' => 'Correction d\'un problème de drag and drop sous Chrome.',
),
),
'dependencies' =>
array (
'framework' =>
array (
'version' => '3.5.1',
'from' => '3.4.5',
),
),
),
28 =>
array (
'version' => '2.3.0',
'date' => '28/07/2021',
'description' => 'Améliorations divers',
'notes' =>
array (
0 =>
array (
'type' => 'ADD',
'code' => 'PGI-3237',
'text' => 'Création du bloc de génération de l\'export produit.',
),
1 =>
array (
'type' => 'ADD',
'code' => 'PGI-3254',
'text' => 'Cacher le bouton d\'export si la clé \'carbon_footprint_catalogue\' est vide.',
),
2 =>
array (
'type' => 'ADD',
'code' => 'PGI-3320',
'text' => 'Ajouter un bouton de vidange du cache sur la page de support.',
),
3 =>
array (
'type' => 'ADD',
'code' => 'PGI-3338',
'text' => 'Ajouter la colonne \'ID-tech\' dans le CSV d\'export du catalogue produits.',
),
4 =>
array (
'type' => 'FIX',
'code' => 'PGI-3253',
'text' => 'Configurer un upgrade pour récupérer la valeur globale du setting \'tree_api_global\'.',
),
5 =>
array (
'type' => 'FIX',
'code' => 'PGI-3250',
'text' => 'Correction du setting "tree_api_server" qui n\'est plus "global".',
),
6 =>
array (
'type' => 'FIX',
'code' => 'PGI-3323',
'text' => 'Problème de nommage des services de filtrage.',
),
7 =>
array (
'type' => 'FIX',
'code' => 'PGI-3339',
'text' => 'Correction du problème de cache de transaction sur les paiements en XTIME.',
),
),
'dependencies' =>
array (
'framework' =>
array (
'version' => '3.6.1',
'from' => '3.5.1',
),
),
),
),
);
