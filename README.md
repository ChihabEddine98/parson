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

PS : le dernier script de **`composer launch`** lance le server sur le port **`DB_PORT`**
Renjoingez votre navigateur et tapez :
**`http://DB_HOST:DB_PORT/`**

# Some Screenshots ( quelques captures !)

![welcome](https://user-images.githubusercontent.com/38104305/82168353-e0be5680-98be-11ea-86dd-52989d4e96f9.JPG)

![register](https://user-images.githubusercontent.com/38104305/82168373-eae05500-98be-11ea-9ebd-c25ea840aa26.JPG)

![login](https://user-images.githubusercontent.com/38104305/82168376-ecaa1880-98be-11ea-9cca-bf1f09113f76.JPG)

![welcome_admin](https://user-images.githubusercontent.com/38104305/82168398-f469bd00-98be-11ea-869a-c961635213d9.JPG)
![users_list](https://user-images.githubusercontent.com/38104305/82168412-ff245200-98be-11ea-8593-42031aea9fdf.JPG)
![cours_list](https://user-images.githubusercontent.com/38104305/82168422-03e90600-98bf-11ea-9843-1fb449ae279a.JPG)
![list_all_cours](https://user-images.githubusercontent.com/38104305/82168424-064b6000-98bf-11ea-9160-566f0fbe8411.JPG)
![results](https://user-images.githubusercontent.com/38104305/82168432-0ba8aa80-98bf-11ea-897c-333c07e0991a.JPG)
![add_ens](https://user-images.githubusercontent.com/38104305/82168442-1105f500-98bf-11ea-8774-1647bd0fda45.JPG)
![stat_admin](https://user-images.githubusercontent.com/38104305/82168450-15321280-98bf-11ea-9c11-27e79e8b264f.JPG)
![profile](https://user-images.githubusercontent.com/38104305/82168485-2b3fd300-98bf-11ea-8c44-2e051bbd63f6.JPG)
![profile_next](https://user-images.githubusercontent.com/38104305/82168592-5aeedb00-98bf-11ea-913f-20d723814ddf.JPG)
![profile_2](https://user-images.githubusercontent.com/38104305/82168495-30048700-98bf-11ea-99ab-e9e9df8ebc0f.JPG)
![student_profile](https://user-images.githubusercontent.com/38104305/82168504-34c93b00-98bf-11ea-9abe-36db55e5adfd.JPG)
![student_result](https://user-images.githubusercontent.com/38104305/82168513-398def00-98bf-11ea-8a26-7d1e0e622874.JPG)
![student_courses](https://user-images.githubusercontent.com/38104305/82168522-3eeb3980-98bf-11ea-85a4-0f67ad11d5fa.JPG)
![add_cours](https://user-images.githubusercontent.com/38104305/82168544-47dc0b00-98bf-11ea-86dd-c625002c8f52.JPG)
![cours_detail](https://user-images.githubusercontent.com/38104305/82168552-4a3e6500-98bf-11ea-8537-38b4c631016c.JPG)
![cours_detail_2](https://user-images.githubusercontent.com/38104305/82168560-4ca0bf00-98bf-11ea-89c0-2029a59cbf42.JPG)
![cours_detail_3](https://user-images.githubusercontent.com/38104305/82168563-4dd1ec00-98bf-11ea-9ec1-407b388b3341.JPG)
![cours_detail_4](https://user-images.githubusercontent.com/38104305/82168569-4f9baf80-98bf-11ea-914d-f14a9a8b5e53.JPG)
![cours_detail_5](https://user-images.githubusercontent.com/38104305/82168578-5296a000-98bf-11ea-8121-18db0d1beda8.JPG)
![solve_exo](https://user-images.githubusercontent.com/38104305/82168584-55919080-98bf-11ea-8a23-e787d7bc5a04.JPG)
![add_solution](https://user-images.githubusercontent.com/38104305/82168616-6a6e2400-98bf-11ea-8a54-0a5e6c05906d.JPG)




