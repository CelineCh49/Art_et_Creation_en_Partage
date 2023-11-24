<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use App\Entity\Category;
use App\Entity\Event;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    protected static string $defaultName = 'app:fixtures:load';
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {


        //Event
        $event1 = new Event();
        $event1->setName('Barjac Hors des sentiers battus - 2023');
        $openingDate = new DateTime('2023-09-09');
        $event1->setOpeningDate($openingDate);
        $closingDate = new DateTime('2023-09-10');
        $event1->setClosingDate($closingDate);
        $event1->setSchedule('de 10h à 19h');
        $event1->setDescription('Cela fait 2 ans que nous organisons une balade artistique à Barjac, petite ville du Gard où l’Art a toute sa place. 
        Chaque année nous sélectionnons une vingtaine d’artistes pour cette rencontre conviviale entre artistes et avec le public qui a lieu durant tout un week-end début septembre.
        En 2023, l’évènement a pris ses quartiers dans 1 mas privé, les artistes ont exposé tant à l’extérieur qu’à l’intérieur pour un week-end festif avec musique, buvette et petite restauration.A nos expositions, vous rencontrerez donc des artistes connus, des professionnels, des artistes moins connus, des artistes n’ayant jamais exposés...mais qui ont tous : une identité artistique propre. Les artistes que nous exposons sont toujours présents lors des évènements : le but étant une rencontre entre artistes et avec le public. Qui mieux que l’artiste peut présenter son travail ?
        Nos artistes : Chaque année ,nous sélectionnons des artistes en fonction de nos coups de cœur, de la beauté de leur création, de l’histoire de l’œuvre, de la maîtrise de leur technique, de leur regard atypique, du message apporté...bref nous nous laissons guider par nos émotions. ');
        $event1->setFacebookLink('https://www.facebook.com/Barjachorsdessentiersbattus/');
        $event1->setFavoriteImage('affiche_barjac.jpg'); //picture should be in public/assets/uploads/event_img
        $manager->persist($event1);
        $manager->flush();
        dump('Evenement créé : ' . $event1->getName());

        //Category
        $categoryNames = ['PEINTURE', 'SCULPTURE', 'PLASTICIEN.NE', 'POESIE','PHOTOGRAPHIE','MUSIQUE'];
        foreach ($categoryNames as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
            dump('Category créée : ' . $category->getName());
        }
        $manager->flush();

        //Admins
        $adminDatas = [
            [
                'firstName' => 'admin',
                'lastName' => 'admin',
                'email' => 'admin@admin.com',
                'telephone' => '',

            ],
            [
                'firstName' => 'Françoise',
                'lastName' => 'GERLACHE',
                'email' => 'francoisegerlache@outlook.fr',
                'telephone' => '0640166505',
                'artist' =>
                [
                    'artistName' => 'Françoise GERLACHE',
                    'description' => "Elle a choisi les enduits à la chaux pour s’exprimer pour des raisons écologiques, mais aussi pour la beauté et la sensualité de la matière. Artiste plasticienne depuis 25 ans, Françoise Gerlache est à l’aise avec les techniques qui régissent son métier. Elle a adopté tant pour la décoration que pour son travail artistique une démarche toute en émotions. Un partage de sensibilité…

                     Passionnée par l’Humain et l’éthnologie, son travail, qu’il soit figuratif ou abstrait est l’expression de ses rencontres au cours de voyages en Asie, en Afrique, mais aussi un Cri pour la sauvegarde des cultures et spiritualités séculaires des peuples premiers que nous sommes entrain de sacrifier.
                     Souvenirs partagés avec des HOMMES de toutes les civilisations, de toutes les cultures, beauté d’un dessin ornant une pièce de vaisselle ou un mausolée….
                     
                     Ses oeuvres expriment la pérénnité des qualités humaines et spirituelles et sont dédiés à l’Etre Humain, cet étrange créature….. Un hommage à l’humanité.
                     
                     Aujourd’hui, installée dans le sud de la France, elle poursuit ses activités d’artiste et continue de donner des cours artistiques et de décoration avec, un plus… La possibilité de loger chez elle en chambre d’hôte ou en gîte. Mais toujours dans l’harmonie de la magie des couleurs et de la nature.
                      ",
                    'email' => 'francoisegerlache@outlook.fr',
                    'telephone' => '',
                    'websiteLink' => '',
                    'facebookLink' => '',
                    'instagramLink' => '',
                    'categories' => ['SCULPTURE'],
                    'favoriteImage' => 'francoise_gerlache_n.jpg',

                ],
            ],
        ];
        foreach ($adminDatas as $adminData) {
            $admin = new User();
            $admin->setFirstName($adminData['firstName']);
            $admin->setLastName($adminData['lastName']);
            $admin->setEmail($adminData['email']);
            $admin->setPassword($this->passwordHasher->hashPassword($admin, '123456'));
            $admin->setRoles(['ROLE_ADMIN']);
            $admin->setTelephone($adminData['telephone']);

            // Create artist and associate to user
            if (isset($adminData['artist'])) {
                $artistData = $adminData['artist'];

                $artist = new Artist();
                $artist->setArtistName($artistData['artistName']);
                $artist->setDescription($artistData['description']);
                $artist->setEmail($artistData['email']);
                $artist->setTelephone($artistData['telephone']);
                $artist->setWebsiteLink($artistData['websiteLink']);
                $artist->setFacebookLink($artistData['facebookLink']);
                $artist->setInstagramLink($artistData['instagramLink']);
                $artist->setFavoriteImage($artistData['favoriteImage']);
                $artist->addEvent($event1);

               

                // Associate category to artist
                $categories = $artistData['categories'];
                foreach ($categories as $categoryName) {
                    $category = $manager->getRepository(Category::class)->findOneBy(['name' => $categoryName]);
                    if ($category) {
                        $artist->addCategory($category);
                    }
                }
                $artist->setUser($admin);
                $admin->setArtist($artist);
                dump('Artist créé : ' . $artist->getArtistName());
                
            }
                $manager->persist($admin);
                dump('Admin créé : ' . $admin->getLastName());
                
            
        }
        $manager->flush();

        // Artists/Users
        $userDatas = [
            [
                'firstName' => 'Anne-Marie',
                'lastName' => 'ADAM',
                'email' => 'annemarieadam1954@gmail.com',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'AMA',
                    'description' => "La sculpture c’est un rêve d’enfant qui est devenu réalité à l’âge adulte après une carrière professionnelle bien remplie. Un changement de vie lui a donné l’occasion de concrétiser ce rêve d'enfant en s'inscrivant à l’école des Beaux Arts de LIEGE sa ville natale. Le modelage de la terre d’après modèle vivant fut pour elle une révélation. On pourrait même parler d’obsession tant l’artiste se réalise et s’exprime à travers cette discipline.
                     La reconnaissance à travers de nombreuses récompenses dont les plus marquantes furent l'attribution du titre de Femme de Cristal dans la catégorie arts et création en 2006 ainsi que la Médaille d’or de la Renaissance culturelle européenne.
                     La sculptrice Anne-Marie Adam sublime les femmes parce qu'elle les comprend. Dans chaque œuvre d'Anne Marie ADAM se reflète et se ressent la grande complicité née entre l'artiste et son modèle.
                     En 2009, Anne Marie ADAM a quitté sa Belgique natale pour Nîmes dans le sud de la France où elle vit et a installé son atelier. Son intégration dans le Languedoc-Roussillon se concrétise par différentes expositions comme le 14 Quai de la Fontaine à Nîmes, ou encore l'Espace Chamson à Alès et en 2013 la participation au salon d'art contemporain à Aix en Provence le Smart, en 2014 exposition dans le Duché d'Uzès.
                     En janvier 2014, 3 pages lui sont consacrées dans la revue Art dans l'Air premier magazine de l'art et des artistes en Languedoc-Roussillon, car elle a été plébiscitée par les abonnés de cette revue. En 2015, elle est sollicitée pour créer un trophée pour la mise à l'honneur d'un grand rejonador espagnol Angel Peralta.
                     Sa devise :  les mains dans la terre pour garder les pieds sur terre   ",
                    'email' => 'annemarieadam1954@gmail.com',
                    'telephone' => '',
                    'websiteLink' => '',
                    'facebookLink' => '',
                    'instagramLink' => '',
                    'categories' => ['SCULPTURE'],
                    'favoriteImage' => 'leçon n°1(1).JPG',

                ],
            ],
            [
                'firstName' => 'Anne',
                'lastName' => 'MAURY',
                'email' => 'Contact@annemaury.fr',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'Anne MAURY',
                    'description' => "Emerveillée par le végétal, son énergie et son foisonnement me fascinent.
                         Optimiste, exalté, vivant et coloré, mon travail est délibérément orienté du côté de
                         l'enchantement du monde.
                         Le végétal, symbole perpétuel et infini est une source inépuisable de recherches
                         graphiques et plastiques.
                         C'est à travers de multiples représentations de cette énergie vitale que je poursuis mon
                         travail de peinture et d'images imprimées.
                         Atelier La Tête dans les Arbres – Le Rouchat -63 520 DOMAIZE",
                    'email' => 'Contact@annemaury.fr',
                    'telephone' => '',
                    'websiteLink' => '',
                    'facebookLink' => '',
                    'instagramLink' => '',
                    'categories' => ['PEINTURE'],
                    'favoriteImage' => 'pink forest.jpg',

                ],
            ],
            [
                'firstName' => 'Antoine',
                'lastName' => 'BRODIN',
                'email' => 'brodinantoine1@gmail.com',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'Antoine BRODIN',
                    'description' => "Impossible (impensable) de commencer à évoquer Antoine Brodin par un bout ou un
                             autre, un début, à l'endroit. Ou à l'envers, d'ailleurs.
                             Comme ses pièces, il faut le prendre à la fois dans un avant, un pendant, un après.
                             On pensera alors qu'il en est de même pour tout le monde, qu'un brin de biographie
                             pourrait étancher une soif de comprendre, de savoir, de classer, de caler et qu'il s'agirait,
                             méthodologiquement parlant, d'une approche cohérente, non définitoire, permettant
                             d'ouvrir à la connaissance du personnage. Là non. Tout bonnement.
                             Il suffit d'avoir conversé sous les étoiles, sur une terrasse, entre les plantes
                             harrisoniennes, de sociologie morinienne, de science fiction damasienne, de
                             considérations jodorowskyennes, d'ouvertures lévi-straussiennes, de quelques fantaisies
                             astrophysiques, de changements de paradigmes, de ses origines, pour savoir qu'il est de
                             la trempe des peu classables et qu'il préfère éviter de l'être, de ceux qui ne marchent pas
                             droit, des aventuriers de la cafetière, des buveurs d'imaginaires, des arpenteurs des
                             grandes marches de l'esprit, des accidentés repétassés, de ceux qui aiment respirer le
                             monde et faire s'en toucher les fils, des curieux, des chercheurs, des pensées
                             labyrinthiques, de ceux qui sont encore en devenir.
                             Il faut faire confiance alors à la première chose qui vient. Les mots qui ont constellés au
                             dessus de nos têtes ? Déplacements, mouvements, équilibres, débordements,
                             augmentations, entropie, partage, contemplation, agitation, mélancolie, ravissement,
                             chaos, cosmos, tous les cosmos.
                             Et la première chose qui vient, c'est un fragment d'histoire recomposée.
                             Extrait de Como Estas de Manuel Fadat (Historien de l’art et commissaire
                             d’exposition, spécialiste de l’art du verre)",
                    'email' => 'brodinantoine1@gmail.com',
                    'telephone' => '',
                    'websiteLink' => '',
                    'facebookLink' => '',
                    'instagramLink' => '',
                    'categories' => ['SCULPTURE'],
                    'favoriteImage' => 'Antoine Brodin-Beaux-Arts-Ncy-011-p.jpg',
                ],
            ],
            [
                'firstName' => 'Aurélie',
                'lastName' => 'MARTINEZ',
                'email' => 'lilimarti@hotmail.fr',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'Aurélie MARTINEZ',
                    'description' => "A mes 26 ans émerge mon premier mandala , naissance d un de mes arts sacrés au service du
                                 deploiement de la beaute l harmonie et la célébration de la diversité sur Gaïa. Dessine moi un
                                 mandala émerge tel un joyau precieux .... En reliance de coeur avec les êtres , les lieux , les
                                 fréquences appelées, l'onde de forme se déploie sur la toile de façon intuitive et libre en se laissant
                                 découvrir dans sa totalité une fois la reliance honorée. c est une joie que de les manifester et de les
                                 remettre à leur gardien destiné. Les mandalas dansent leur grand mystère en offrant leur medecine
                                 sacré en nourissant l'Être, l Áme et l'Esprit de ceux et celles qui ne voient bien que par le coeur . Ils
                                 embellissent et révèlent les intérieurs de nos temples sacrés. Ils invitent à l'activation des ressources,
                                 des dons et talents innés porté par la douce promesse scellée d'une alliance en purete de coeur pour l
                                 émergence , le déploiement et l'expression de notre Nature véritable en cocreation avec le Grand
                                 Vivant.",
                    'email' => 'lilimarti@hotmail.fr',
                    'telephone' => '',
                    'websiteLink' => '',
                    'facebookLink' => '',
                    'instagramLink' => '',
                    'categories' => ['PEINTURE'],
                    'favoriteImage' => '1692339276001.jpg',
                ],
            ],
            [
                'firstName' => 'Christian',
                'lastName' => 'VILADENT',
                'email' => 'christian.viladent@gmail.com',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'Christian VILADENT',
                    'description' => "L'acier est réputé pour être un matériau froid et rigide, mon but est de tenter de le dompter afin de rendre hommage à la nature, sa diversité et ses formes douces. Ainsi, pour toutes mes créations, je pars d'une feuille d'acier de 1mm, ma page blanche en quelque sorte, que je martèle, chauffe, modèle, afin d'en faire jaillir un animal marin, un arbre, une plante, un personnage..Je ne peins aucune de mes créations mais les laisse à l'état brut de l'acier ou laisse le temps et la rouille s'installer puis les patinent en teintes chaudes.
                                     Je vis et travaille à Barjac depuis plusieurs années.
                                     ",
                    'email' => 'christian.viladent@gmail.com',
                    'telephone' => '',
                    'websiteLink' => '',
                    'facebookLink' => '',
                    'instagramLink' => '',
                    'categories' => ['SCULPTURE'],
                    'favoriteImage' => 'Nautile achevé.JPG',
                ],
            ],
            [
                'firstName' => 'Delphine',
                'lastName' => 'VILLETET',
                'email' => 'delphinevteisseire@yahoo.fr',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'Delphine VILLETET',
                    'description' => "La création fait partie de la vie de Delphine depuis toujours.
                                         Elle commença avec des illustrations puis des sculptures en
                                         laine, et enfin de la peinture vers 2005.
                                         Autodidacte, elle devient élève de plusieurs artistes et suit
                                         des cours dans plusieurs disciplines.
                                         La plupart de ses créations sont peintes d’après des
                                         « «flash »qui s’imposent à elle avec tous les détails,
                                         notamment la série « êtres humains, êtres
                                         multidimensionnels » qui comprend 9 tableaux que Delphine
                                         a peaufiné pendant 9 ans.
                                         Pour ses nouvelles créations, „la tête vide“, elle laisse libre
                                         court aux pinceaux …..
                                         ",
                    'email' => 'delphinevteisseire@yahoo.fr',
                    'telephone' => '',
                    'websiteLink' => '',
                    'facebookLink' => '',
                    'instagramLink' => '',
                    'categories' => ['PEINTURE'],
                    'favoriteImage' => 'delphineV_3.jpg',
                ],
            ],
            [
                'firstName' => 'Françoise',
                'lastName' => 'LACROIX',
                'email' => 'francoiselacroixlegarn@gmail.com',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'Françoise LACROIX',
                    'description' => "Entre villages et paysages, j'aime glaner, dénicher un morceau de bois ou de racine ...Ce bout de
                                             nature qui m'inspire et qu’il me plaît de marier à l'acier que je travaille plie et soude afin qu'il puisse
                                             faire écho et vienne prolonger mon intervention sur le bois.
                                             ",
                    'email' => 'francoiselacroixlegarn@gmail.com',
                    'telephone' => '',
                    'websiteLink' => '',
                    'facebookLink' => '',
                    'instagramLink' => '',
                    'categories' => ['SCULPTURE'],
                    'favoriteImage' => 'francoise_lacroix_3.jpg',
                ],
            ],
            [
                'firstName' => 'Frédéric',
                'lastName' => 'MULATIER',
                'email' => 'fredericmulatier@gmail.com',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'Frédéric MULATIER',
                    'description' => "Depuis très longtemps, des femmes et des hommes tressent. Bien plus tard, d’autres tapissèrent des
                                                 vanneries d’argile. Les entrelacs de branches deviennent alors tuteurs de la terre qui les avait
                                                 auparavant nourris. Le feu vient ensuite sceller cette union du végétal et du minéral, redonnant à la
                                                 terre sa force et célébrant cette naissance, cette rencontre, ces premières poteries empreintées.
                                                 Rendre au tressage une partie de son histoire fossilisée dans des fragments déterrés. L’histoire que
                                                 je raconte naît de cette mémoire archéologique. J’imagine, je voyage vers ces temps oubliés.
                                                 Sculpter comme on écrirait : les gestes sont les mêmes, ils poursuivent l’écriture, ils continuent de
                                                 raconter la vie, l’espoir, l’émotion peut-être… Frédéric Mulatier, mai 2023
                                                 ",
                    'email' => 'francoiselacroixlegarn@gmail.com',
                    'telephone' => '',
                    'websiteLink' => '',
                    'facebookLink' => '',
                    'instagramLink' => '',
                    'categories' => ['SCULPTURE'],
                    'favoriteImage' => 'Pariétale-2-F.Mulatier.jpg',
                ],
            ],
            [
                'firstName' => 'Ghislaine',
                'lastName' => 'DEROUGE',
                'email' => 'ghislainederouge@exemple.com',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'Ghislaine DEROUGE',
                    'description' => "Installation de grands bustes en grès chamotté, avec
                    engobes et émaux,
                    ajouts de bois, rotin, pierres, métal...
                    Le féminin s’installe, imaginaire, modelée dans la
                    matière, regard frontal expressif ou porté vers le ciel,
                    comme pour questionner le spectateur face au lien
                    qu’il entretient avec la nature, la vie,
                    un autre regard se pose au delà d’une réalité
                    conforme
                    Les Animaux-humains sont inspirés du Kangourou,
                    de la biche, du bélier, du chat, du Dieu Paon, de la
                    mythologie, des mondes océaniques,
                    leur regard humain interroge sur le rapport avec le
                    vivant, le monde
                    Les 2 genres se mélangent et laissent place à un
                    univers féerique de contes sortis d’Afrique, d’Asie,
                    d’autres temps",
                    'email' => 'ghislainederouge@exemple.com',
                    'telephone' => '',
                    'websiteLink' => 'ghislainederougé.fr',
                    'facebookLink' => '',
                    'instagramLink' => '',
                    'categories' => ['SCULPTURE'],
                    'favoriteImage' => 'ghislaine_derouge_DSCN5123.JPG',
                ],
            ],
            [
                'firstName' => 'Hervyane,',
                'lastName' => 'BOUVIER',
                'email' => 'bouvierhervyane@gmail.com',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'Hervyane BOUVIER',
                    'description' => "Chacun est solitude dans toute l'éternité. Rien ne s’assujettit et rien ne s’apprivoise.
                    Mais les fées penchées sur mon berceau ont caché une plume pour écrire à l’encre d’or,
                    telle une enluminure, tout cet imaginaire qui est mon univers.
                    Etre l'horizon vermeil au soleil de pourpre, le vieil arbre méditant au fond de la
                    forêt, les perles d’un collier de quelques fleurs de givre, l’embrasement d’un vitrail
                    d’une vieille chapelle, vent moqueur chevauchant les marées de septembre, écriture
                    apocryphe d’une autre galaxie.
                    Etre ce que je possède sans même posséder, en restant solitude pour toute éternité.
                                                 ",
                    'email' => 'bouvierhervyane@gmail.com',
                    'telephone' => '',
                    'websiteLink' => '',
                    'facebookLink' => '',
                    'instagramLink' => '',
                    'categories' => ['POESIE'],
                    'favoriteImage' => 'hervphoto.jpg',
                ],
            ],
            [
                'firstName' => 'Jean-Marc',
                'lastName' => 'FRAISSE',
                'email' => 'jean-marc.fraisse@orange.fr',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'Jean-Marc FRAISSE',
                    'description' => "Encre de chine, Figuratif, Abstrait.
                    Harmonisation d’élèments nobles avec feuilles d’or.
                    Toujours tourné vers le dessin et l’art, j’ai eu le plaisir de créer
                    mon atelier-galerie en Ardèche et présenter ainsi mes oeuvres. Le
                    succès fut là.
                    Cela m’a permis d’innover mes techniques, mes compositions de
                    pigments et ensuite de m’orienter vers d’autres créations, qui ont
                    revelé le naturel tel un chef cuisinier qui innove ses plats.
                    Expositions : Paris : Assemblée Nationale, Pierre Cardin. Îles
                    Canaris : Lanzarote. Lion’s club international. Apis mondial. Le
                    Puy en Velay : basilique. Bann’art. Galerie Séraphine etc etc..",
                    'email' => 'jean-marc.fraisse@orange.fr',
                    'telephone' => '',
                    'websiteLink' => '',
                    'facebookLink' => '',
                    'instagramLink' => '',
                    'categories' => ['PEINTURE'],
                    'favoriteImage' => 'jean-marc_fraisse_46x60.JPG',
                ],
            ],
            [
                'firstName' => 'Jean-Pierre',
                'lastName' => 'THEIN',
                'email' => 'jpthein@free.fr',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'Jean-Pierre THEIN',
                    'description' => "Je suis né à Nîmes le 21 Juin 1961 et vis actuellement à Saint Chaptes dans le Gard.
                    En 1977 j’obtiens un BEP agencement et mobilier et deviens Ebéniste. Après un
                    complément de formation chez les compagnons, j’intègre l’entreprise familiale
                    comme ouvrier ébéniste. En 1982 durant 6 ans j’ai suivi une formation en sculpture
                    sur bois avec le sculpteur André Liez. En 1989 je reprends l’entreprise familiale et
                    deviens artisan, puis Maitre artisan avec l’obtention d’un brevet de Maitrise
                    Supérieur. L’entreprise constituée de 4 salariés a pour activité : restauration, copies
                    d’ancien, création de meubles et sculpture.
                    En 2006 je convertis mon activité artisanale en activité artistique et continue seul
                    sculpture, peinture et dessin. Depuis j’expose en France, participe à des symposiums
                    de sculpture en France et en Europe. J’ai obtenu plusieurs prix de sculpture et de
                    peinture.
                    J’ai réalisé plusieurs sculptures monumentales qui sont sur des espaces publiques.
                    J’ai aussi réalisé un bas-relief sculpté de 30m de long sur l’histoire de la réforme qui
                    circule en France.
                    Depuis 2019 je réalise ponctuellement pour l’entreprise Steinfeld des sculptures
                    d’animaux en bois de taille réelle (Rhinocéros, lion etc.)
                    En parallèle, j’ai été professeur de sculpture au Lycée des métiers d’art à Uzès de
                    2006 à 2018. J’ai quitté le lycée pour me consacrer pleinement à mon activité
                    artistique.
                    Mon travail artistique passe de la sculpture bois à la sculpture pierre et au modelage,
                    à la peinture à l’huile, au pastel et parfois par le métal.
                    Toutes les oeuvres sont porteuses de sens, soit de valeurs humanistes, soit
                    environnementales. J’aime bien aussi m’exprimer au travers de la mythologie et
                    parfois représenter des personnages historiques afin de montrer leur importance dans
                    notre société actuelle. ",
                    'email' => 'jpthein@free.fr',
                    'telephone' => '',
                    'websiteLink' => '',
                    'facebookLink' => '',
                    'instagramLink' => '',
                    'categories' => ['SCULPTURE'],
                    'favoriteImage' => 'JeanPierreThein46(1).jpg',
                ],
            ],
            [
                'firstName' => 'Lüraimondi',
                'lastName' => 'Lüraimondi',
                'email' => 'lrlpcontact@gmail.com',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'Lüraimondi',
                    'description' => "Photodidacte, mon travail est basé sur des informations élaborées par nos sens. Mes propositions
                    photographiques se composent de minéraux et de végétaux, elles offrent une vision subjective qui
                    nous renvoie à nos miroirs intérieurs multiples. Selon la symbolique de la symétrie je met l’accent
                    sur l’humain et ses relation à la nature, à la mémoire, à la perfection et à la beauté. L’observateur est
                    amené à se questionner sur ce qu’il perçois de ce qui l’entoure.",
                    'email' => 'lrlpcontact@gmail.com',
                    'telephone' => '',
                    'websiteLink' => '',
                    'facebookLink' => '',
                    'instagramLink' => '',
                    'categories' => ['PHOTOGRAPHIE'],
                    'favoriteImage' => '220523_1©LüRaimondi_180.jpg',
                ],
            ],
            [
                'firstName' => 'Oona',
                'lastName' => 'Lanana',
                'email' => 'oona.la.nana.@gmail.com',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'Oona La Nana',
                    'description' => "Grâce à mon art, je me définis comme un canal pour faire émerger, chez celui qui plonge dans mes
                    oeuvres, des messages enfouis dans l'inconscient.
                    Ces oeuvres s'inscrivent entre l'abstrait et le figuratif. Il n'y a pas de bonne ou mauvaise perception :
                    je ne souhaite pas dire au public, quoi, ou comment penser. Je souhaite le laisser libre d'être acteur,
                    afin qu'il se fasse sa propre interprétation. Je lui fournis juste des clés, des pistes, afin qu'il puisse
                    s'évader par l'esprit, faire surgir des pensées, des émotions qu'il n'imaginait pas voir surgir à ce
                    moment-là.
                    Le maître mot est le lâcher-prise : c'est par lui que nous accéderons aux messages cachés de l'art.
                    Je terminerai par une citation d'Antoine de Saint-Exupéry qui résume bien mon travail : L'essentiel
                    est invisible pour les yeux.",
                    'email' => 'oona.la.nana.@gmail.com',
                    'telephone' => '',
                    'websiteLink' => '',
                    'facebookLink' => '',
                    'instagramLink' => '',
                    'categories' => ['PHOTOGRAPHIE'],
                    'favoriteImage' => 'la sirène tigre de oona.la.nana.jpg',
                ],
            ],
            [
                'firstName' => 'Philippe',
                'lastName' => 'DUPAYAGE',
                'email' => 'philippe.dupayage@hotmail.com',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'Philippe DUPAYAGE ',
                    'description' => "Pas de description pour l'instant :(",
                    'email' => 'philippe.dupayage@hotmail.com',
                    'telephone' => '',
                    'websiteLink' => '',
                    'facebookLink' => '',
                    'instagramLink' => '',
                    'categories' => ['PEINTURE'],
                    'favoriteImage' => 'LA CICATRICE.png',
                ],
            ],
            [
                'firstName' => 'Philippe',
                'lastName' => 'LONZI',
                'email' => 'lonzi@outlook.fr',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'Philippe LONZI ',
                    'description' => "Artiste Peintre et Sculpteur, philippe LONZI est un créateur né !
                    Il respecte les autres et ses propres règles pour avoir envie d'avancer, le passé est déjà passé.
                    Autodidacte, libre et fier de l ’être, cet homme consacrera toute son existence à rechercher ce qui n ’existe
                    pas encore pour essayer de le réaliser.
                    C ’est ainsi que sont nés un certain nombre de brevets dans de nombreux domaines très divers, raison pour
                    laquelle ses oeuvres sont si différentes de celles des autres.
                    Philippe LONZI s’est intéressé à l ’art il y a une vingtaine d ’années, il aime dire qu ’il sculpte dans l ’acier,
                    comme d ’autres dans la pierre ou le bois.
                    En effet, il pense et taille ses formes dans des tôles neuves, aussi vierges que la page de l ’écrivain. il se
                    refuse à assembler la moindre pièce faite par un autre.
                    Ses réalisations sont à la fois pures, équilibrées, mais dans un style très contemporain, ce qui ne nous gène
                    en rien pour nous évader dans un monde de rêve et de fantaisie.
                    Philippe LONZI est, avant tout, un passionné, dans son atelier et lieu de vie, il cherche, sans cesse à
                    concevoir de nouvelles manières de nous surprendre.
                    Profondément tourné vers les autres, il recherche en permanence de nouveaux lieux d ’exposition, se
                    nourrissant de ces échanges avec le public.
                    D ’ailleurs, lors de ces manifestations, il utilise des plots lumineux, qu ’il a créé, pour mettre en lumière et en
                    ombre ses oeuvres, qui deviennent presque vivantes.",
                    'email' => 'lonzi@outlook.fr',
                    'telephone' => '',
                    'websiteLink' => '',
                    'facebookLink' => '',
                    'instagramLink' => '',
                    'categories' => ['PEINTURE', 'SCULPTURE', 'POESIE'],
                    'favoriteImage' => 'LA PETILLANTE-PT.jpg',
                ],
            ],
            [
                'firstName' => 'Guillaume',
                'lastName' => 'Voiriot',
                'email' => 'guillaumevoiriot@exemple.com',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'Poulp',
                    'description' => "Poulp est multi instrumentaliste et compositeur de talent . Il nous a concocté une variation musicale
                    autour de musique du monde : musique indienne, bossanova, reggae mais aussi du rock progressif.
                    « Poulp de son vrai nom Guillaume Voiriot , a étudié le Jazz et la musique Classique Indienne, il
                    s'est intéressé aux musiques traditionnelles. Il aime la diversité des timbres de ces instruments et est
                    aussi fan de rock progressif.
                    Il compose ses musiques et joue quasiment tous les instruments sur ses enregistrements.
                    Son instrument principal est la guitare mais il joue également du bansuri (flûte traditionnelle
                    indienne), de la flûte traversière, de la basse, du piano, du chant, de l’harmonica, du jembé…
                    Il est dans son élément dans l'improvisation et les musiques dites modales. Chaque mode (gamme)
                    crée une ambiance particulière, qui fait ressortir toutes les expressions et les sentiments qui lui sont
                    associés.
                    Passionné de musique, il aime l'écouter, la jouer et la partager.",
                    'email' => 'guillaumevoiriot@exemple.com',
                    'telephone' => '',
                    'websiteLink' => '',
                    'facebookLink' => '',
                    'instagramLink' => '',
                    'categories' => ['MUSIQUE'],
                    'favoriteImage' => 'POULP.jpg',
                ],
            ],
            [
                'firstName' => 'Virginie',
                'lastName' => 'Chomette',
                'email' => 'virginie.chomette07@orange.fr',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'Virginie Chomette',
                    'description' => "... J 'AI  FAIT  LE  PAS ... J 'AI  FAIT  ET  DEFAIT ... J 'AI  FAIT  LA  FETE ...J 'AI  FAIT  LE  SILENCE ... J 'AI  FAIT SANS EFFETS ... J'AI FAIT DES FEES...J 'AI  FAIT DES ENFANTS … J AI FAIT  LA  SOURDE  OREILLE ... J 'AI  FAIT LA BOUCHE COUSUE...J 'AI  FAIT DANS  LE  DETAIL ... J 'AI  FAIT  DANS  L 'URGENCE ... J 'AI  FAIT DES  NOEUDS... J 'AI  FAIT  COMME  J 'AI  PU ?.. J 'AI  FAIT  DES  COULEURS ... J 'AI  FAIT  LE  CONTOUR ... J 'AI  FAIT  LA  DEBILE ... J 'AI  FAIT  EN  LIBERTE.. .J 'AI  FAIT  DU  DESORDRE ... J 'AI  FAIT  DANS LA  DENTELLE ... J 'AI FAIT  L 'ERMITE ... J 'AI  FAIT  DES  MIETTES ... J AI  FAIT  SANS  FIN ...J 'AI  FAIT  GRANDIR ... J 'AI  FAIT  DES REVES ... J 'AI  FAIT  DES  TRUCS...J 'AI  RIEN  FAIT ...J 'AI  FAIT COMME IL ME PLAIT...TOUT A FAIT... TOUT A  FAIRE...TOUT A ETRE...",
                    'email' => 'virginie.chomette07@orange.fr',
                    'telephone' => '',
                    'websiteLink' => '',
                    'facebookLink' => '',
                    'instagramLink' => '',
                    'categories' => ['PLASTICIEN.NE'],
                    'favoriteImage' => 'chomette_image00014.jpeg',
                ],
            ],
        ];
        foreach ($userDatas as $userData) {
            $user = new User();
            $user->setFirstName($userData['firstName']);
            $user->setLastName($userData['lastName']);
            $user->setEmail($userData['email']);
            $user->setPassword($this->passwordHasher->hashPassword($user, '123456'));
            $user->setRoles(['ROLE_USER']);
            $user->setTelephone($userData['telephone']);

            // Create artist and associate to user
            if (isset($userData['artist'])) {
                $artistData = $userData['artist'];

                $artist = new Artist();
                $artist->setArtistName($artistData['artistName']);
                $artist->setDescription($artistData['description']);
                $artist->setEmail($artistData['email']);
                $artist->setTelephone($artistData['telephone']);
                $artist->setWebsiteLink($artistData['websiteLink']);
                $artist->setFacebookLink($artistData['facebookLink']);
                $artist->setInstagramLink($artistData['instagramLink']);
                $artist->setFavoriteImage($artistData['favoriteImage']);
                $artist->addEvent($event1);

                // Associate category to artist
                $categories = $artistData['categories'];
                foreach ($categories as $categoryName) {
                    $category = $manager->getRepository(Category::class)->findOneBy(['name' => $categoryName]);
                    if ($category) {
                        $artist->addCategory($category);
                    }
                }


                $artist->setUser($user);
                $user->setArtist($artist);

                $manager->persist($user);
                dump('User créé : ' . $user->getLastName());
                dump('Artist créé : ' . $artist->getArtistName());
            }
        }
        $manager->flush();
    }
}
