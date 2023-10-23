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
        $categoryNames = ['Peintre', 'Sculpteur', 'Autre'];
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
                'firstName' => 'Françoise',
                'lastName' => 'GERLACHE',
                'email' => 'artetcreationenpartage@gmail.com',
                'telephone' => '0640166505',
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
                    ['artistName' => 'Anne-Marie',
                     'description' => 'ADAM',
                     'email' => 'annemarieadam1954@gmail.com',
                     'telephone' => '',
                     'websiteLink' => '',
                     'facebookLink' => '',
                     'instagramLink' => '',
                     'categories' => ['Peintre'],
                     'favoriteImage' => 'leçon n°1(1).JPG',
        
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
