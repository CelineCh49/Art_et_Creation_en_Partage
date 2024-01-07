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
        $categoryNames = ['PEINTURE', 'SCULPTURE', 'POESIE','PHOTOGRAPHIE','MUSIQUE'];
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
        ];
        foreach ($adminDatas as $adminData) {
            $admin = new User();
            $admin->setFirstName($adminData['firstName']);
            $admin->setLastName($adminData['lastName']);
            $admin->setEmail($adminData['email']);
            $admin->setPassword($this->passwordHasher->hashPassword($admin, '123456'));
            $admin->setRoles(['ROLE_ADMIN']);
            $admin->setTelephone($adminData['telephone']);
        }
        $manager->persist($admin);
        $manager->flush();
        dump('Admin créé : ' . $admin->getLastName());

        // Artists/Users
        $userDatas = [
            [
                'firstName' => 'Anne-Marie',
                'lastName' => 'ABCD',
                'email' => 'annemarieabcd@exemple.com',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'AMABCD',
                    'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus harum in architecto necessitatibus magni debitis aliquam corrupti, maxime rem quam similique quisquam vero sapiente perspiciatis minus voluptatibus, ducimus molestiae quasi? Lorem ipsum, dolor sit amet consectetur adipisicing elit. Unde sunt rem cumque dignissimos sint hic facere dolorum nobis, cupiditate distinctio pariatur aliquam consequatur officiis quasi nihil nulla ullam accusamus praesentium.
                     Quia voluptate sunt reiciendis! Veniam, eos earum laudantium itaque ut at molestias quisquam quod eligendi. Omnis animi cupiditate nihil vero voluptatem. Iure cumque odit optio excepturi mollitia, vel aliquam atque!
                     Tempora dicta quas laborum itaque quo, veniam ullam ab autem est eligendi aut iusto exercitationem facere minima quisquam perferendis doloremque earum aperiam accusamus error, animi atque. Laudantium aperiam alias molestias?",
                    'email' => 'annemarieabcd@exemple.com',
                    'telephone' => '01 02 03 04 05',
                    'websiteLink' => 'https://exemple.com',
                    'facebookLink' => '',
                    'instagramLink' => 'https://exemple.com',
                    'categories' => ['SCULPTURE'],
                    'favoriteImage' => 'sculptures-2209152_1280.jpg',

                ],
            ],
            [
                'firstName' => 'Anne',
                'lastName' => 'MARTIN',
                'email' => 'Contact@annemartin.fr',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'Anne MARTIN',
                    'description' => "LoremLorem ipsum dolor sit amet consectetur adipisicing elit. Sapiente quod ipsum in hic voluptates facilis numquam nostrum eveniet ullam! Quo quaerat voluptatum enim culpa, voluptates non quasi obcaecati et earum!",
                    'email' => 'contact@annemartin.fr',
                    'telephone' => '',
                    'websiteLink' => '',
                    'facebookLink' => 'https://exemple.com',
                    'instagramLink' => 'https://exemple.com',
                    'categories' => ['PEINTURE'],
                    'favoriteImage' => 'girl-2696947_1280.jpg',

                ],
            ],
            [
                'firstName' => 'Antoine',
                'lastName' => 'BODIN',
                'email' => 'bodinantoine1@exemple.com',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'Antoine BODIN',
                    'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Autem voluptatum molestiae nostrum fuga architecto excepturi totam omnis voluptatibus maxime iure possimus necessitatibus modi, rem numquam laborum fugiat asperiores officia explicabo.
                             Natus vitae aliquid a sed nobis sapiente consequatur dolor, inventore quia aut eius magnam vero iste necessitatibus pariatur dicta dolore nihil repellat? Vero veniam provident consequatur veritatis, earum hic id!",
                    'email' => 'bodinantoine@exemple.com',
                    'telephone' => '',
                    'websiteLink' => '',
                    'facebookLink' => 'https://exemple.com',
                    'instagramLink' => '',
                    'categories' => ['SCULPTURE'],
                    'favoriteImage' => 'sandburg-1639999_1280.jpg',
                ],
            ],
            [
                'firstName' => 'Aurélie',
                'lastName' => 'SANCHA',
                'email' => 'lilisanc@exemple.fr',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'Aurélie',
                    'description' =>"Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa voluptatibus porro harum reprehenderit", 
                    'email' => 'lilisanc@exemple.fr',
                    'telephone' => '',
                    'websiteLink' => 'https://exemple.com',
                    'facebookLink' => 'https://exemple.com',
                    'instagramLink' => '',
                    'categories' => ['PEINTURE'],
                    'favoriteImage' => 'still-life-562357_1280.jpg',
                ],
            ],
            [
                'firstName' => 'Christian',
                'lastName' => 'VILLE',
                'email' => 'christian.ville@exemple.com',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'ChriVIL',
                    'description' => "Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptate inventore, consectetur porro expedita dolor aliquam nam nisi soluta quos repellendus odio rem obcaecati fugit velit numquam tenetur quasi eveniet et.",
                    'email' => 'christian.ville@exemple.com',
                    'telephone' => '',
                    'websiteLink' => 'https://exemple.com',
                    'facebookLink' => '',
                    'instagramLink' => 'https://exemple.com',
                    'categories' => ['SCULPTURE'],
                    'favoriteImage' => 'zen-509371_1280.jpg',
                ],
            ],
            [
                'firstName' => 'Delphine',
                'lastName' => 'TET',
                'email' => 'delphinetet@exemple.fr',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'Delphine TET',
                    'description' => "Lorem ipsum dolor sit, amet consectetur adipisicing elit. Minus ad quasi eos repudiandae iste at? Praesentium, corrupti ea hic libero quaerat autem eveniet pariatur maiores. Blanditiis aut maiores eos distinctio?
                                         Ratione, dolores amet, consequuntur quaerat ut pariatur, reprehenderit laudantium temporibus aperiam nesciunt tenetur! Vel iusto harum distinctio delectus! Architecto eum ullam aperiam animi dolores ratione aspernatur perspiciatis esse voluptate ab.",
                    'email' => 'delphinevteisseire@yahoo.fr',
                    'telephone' => '06 01 02 03 04',
                    'websiteLink' => 'https://exemple.com',
                    'facebookLink' => 'https://exemple.com',
                    'instagramLink' => '',
                    'categories' => ['PEINTURE'],
                    'favoriteImage' => 'colorful-2468874_1280.jpg',
                ],
            ],
            [
                'firstName' => 'Françoise',
                'lastName' => 'LAIX',
                'email' => 'francoiselaix@exemple.com',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'FLAIX',
                    'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatem corrupti consequuntur ipsa reprehenderit nobis nam harum eveniet exercitationem libero reiciendis? Iure delectus accusamus eaque! Dolorum expedita nobis modi deserunt nihil.",
                    'email' => 'francoiselaix@exemple.com',
                    'telephone' => '',
                    'websiteLink' => '',
                    'facebookLink' => 'https://exemple.com',
                    'instagramLink' => 'https://exemple.com',
                    'categories' => ['SCULPTURE'],
                    'favoriteImage' => 'statue-1275469_1280.jpg',
                ],
            ],
            [
                'firstName' => 'Frédéric',
                'lastName' => 'MULIER',
                'email' => 'fredericmulier@exemple.com',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'Frédéric MULIER',
                    'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorem aut ad id pariatur provident beatae sunt doloremque praesentium veritatis cum architecto, quas iusto, laudantium quod, rem temporibus dolores autem? Obcaecati?
                                                 Ratione voluptatum harum voluptatem quidem eos nulla id, pariatur obcaecati quasi deleniti maiores, dicta ullam quae molestiae est illum voluptates incidunt? Voluptatum ducimus voluptate maxime architecto veniam repellat neque non?
                                                 Nostrum nemo atque doloribus ipsum alias consectetur fuga reiciendis excepturi! Soluta iste nostrum cum recusandae, laborum eligendi adipisci temporibus qui! At cupiditate sapiente iste, possimus tempora est quaerat corporis tenetur?
                                                 Maiores deserunt similique ab sint, aspernatur consequuntur molestiae, debitis ad minima necessitatibus animi quos tenetur ipsum dolor dicta pariatur repudiandae incidunt quis dolore ipsam at mollitia! Sunt vel ipsum quod?",
                'email' => 'fredericmulier@exemple.com',
                    'telephone' => '01 02 03 04 05',
                    'websiteLink' => 'https://exemple.com',
                    'facebookLink' => '',
                    'instagramLink' => 'https://exemple.com',
                    'categories' => ['PHOTOGRAPHIE'],
                    'favoriteImage' => 'painting-911804_1280.jpg',
                ],
            ],
            [
                'firstName' => 'Ghislaine',
                'lastName' => 'DOUGE',
                'email' => 'ghislainedouge@exemple.com',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'Ghislaine DOUGE',
                    'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam deserunt esse iure. Excepturi, fugit sit. Perferendis laborum voluptatibus rem nesciunt qui rerum quaerat sit iste, voluptas facere, animi dolorum fugiat?
                    Quisquam magnam labore aliquid voluptas facilis. Voluptates cumque repellat blanditiis dolore laborum commodi. Omnis consequuntur iure possimus nemo, deserunt quasi autem quaerat, sit adipisci sequi, repudiandae saepe facere similique obcaecati!",
                    'email' => 'ghislainedouge@exemple.com',
                    'telephone' => '01 02 03 04 05',
                    'websiteLink' => 'ghislainedouge.com',
                    'facebookLink' => 'https://exemple.com',
                    'instagramLink' => '',
                    'categories' => ['POESIE'],
                    'favoriteImage' => 'book-2115176_1280.jpg',
                ],
            ],
            [
                'firstName' => 'Hervé,',
                'lastName' => 'BOURIER',
                'email' => 'bourierherve@exemple.com',
                'telephone' => '',
                'artist' =>
                [
                    'artistName' => 'Her BOUVIER',
                    'description' => "Lorem ipsum dolor sit, amet consectetur adipisicing elit. Molestias explicabo libero consectetur ab voluptate ipsum odit repudiandae quam iusto praesentium voluptatum qui commodi, harum porro ullam neque adipisci nostrum magni.",
                    'email' => 'bouvierherve@exemple.com',
                    'telephone' => '',
                    'websiteLink' => '',
                    'facebookLink' => 'https://exemple.com',
                    'instagramLink' => 'https://exemple.com',
                    'categories' => ['POESIE'],
                    'favoriteImage' => 'business-3240767_1280.jpg',
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
