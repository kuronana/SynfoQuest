way :

1 : installer symfo.
2 : crée le controlleur-> extends d'abstract, -> namespace App\..., -> crée methode + route.
3 : modifier .env pour initialiser la co à la db via un ORM, puis faire php bin/console doctrine:database:create .
4 : make:entity, puis a l'issu, faire un make:migration pour generer une class migration versionné incluant l'ordre donné (dans ce cas là, une creation d'entity) ainsi que l'ordre contraire.
5 : doctrine:migration:migrate pour mettre a jour la db avec la migration.
6 : pour cree des foreign key, mettre la relation souhaité dans le type.

 
 