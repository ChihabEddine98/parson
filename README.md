# [ParSon]  le e-learning est le sujet du jour !

## Réalisé Par :
  |   Nom & Prénoms |N_Etudiant| ID_Gitlab | Groupe |
|----------------|-------------------------------|-----------------------------|-------|
|Benamara Abdelkader Chihab | `-- 21808027 --` | `benamara` |    Maths-Info     
|Djelid Aymen | `-- 21810771 --` | `djelid` |  Info4 |


# Comment le lancer pour la premiere fois 
Il faut bien-sur installer tout les packages utilisées  à l'aide de :
>  **composer** install

Tout au début il faudra faire vos configs pour la base de données dans le fichier  [.env](https://github.com/ChihabEddine98/parson/blob/master/parson/.env) 
`/parson/.env`
en  méttant :
`DB_NAME` : nom de la base de donnée
`DB_USER` : nom d'utilisateur
`DB_PASSWORD` : mot de passe 
`DB_HOST` : la hôte !

Dans le cas ou vous utilisez **lulu** pour tester 
**`etu21808027`** et le host à **`lampe`** vous pouvez donc les modifier selon les besoin et selon vos configurations !

Et puis après un petit
>  **composer** launch
( elle va exécuter en background des scripts pour les migrations 
& les fixtures avec des données assez cool ! ) 




# Exécutons le code ensemble !
Apres la première exécution vous aurez automatiquement  des utilisateurs pour tester 

Vous aurez un utilisateur crée du rôle **ADMIN** avec les informations suivantes : 
> **email :  admin@esi.dz**
> **password :  admin**

Vous aurez 3 utilisateurs crée du rôle **ENSEIGNANT** avec les informations suivantes : 
> **email :  ens(%var)@mail.com**
> **password :  123**
> 
>  Avec %var : {1,2,3]  // exemple ens1@mail.com

Vous aurez 10 utilisateurs crée du rôle **ETUDIANT** avec les informations suivantes : 
> **email :  student(%var)@mail.com**
> **password :  123**
> 
>  Avec %var : {1,2,3,...,10]  // exemple student9@mail.com

Maintenant choisissez les trois différents rôles pour pouvoir remarquer la différence !
>  **composer** start
>  ( en background php bin/console server:run )

PS : le dernier script de **`composer launch`** lance le server sur le port **`8000`**
Renjoingez votre navigateur et tapez :
**`http://DB_HOST:8000/`**

# Some Screenshots ( quelques captures !)
