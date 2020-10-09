<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
     
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker=Factory::create('FR-fr');

        $adminRole = new Role();
        $adminRole->setTitle("ROLE_ADMIN");
        $manager->persist($adminRole);
        $adminRole1 = new Role();
        $adminRole1->setTitle("ROLE_MANAGER");
        $manager->persist($adminRole1);

        $adminUser = new User();
        $adminUser->setFirstName("OUSSAMA")
            ->setLastName("JOMAA")
            ->setEmail("osm70@gmx.com")
            ->setHash($this->encoder->encodePassword($adminUser,"password"))
            ->setIntroduction($faker->sentence())
            ->setDescription('<p>' . join('</p><p>', $faker->paragraphs()) . '</p>')
            ->setPicture("https://placehold.it/128x128")
            ->addUserRole($adminRole)
            ->addUserRole($adminRole1);
        $manager->persist($adminUser);

        // Nous gérons les users
        $users = [];
        $genres = ["male","female"];

        for ($i=1; $i<10; $i++){
            $user = new User();
            $genre = $faker->randomElement($genres);

            $hash = $this->encoder->encodePassword($user, 'password');
            
            $picture = "https://randomuser.me/api/portraits/";
            $pictureId = $faker->numberBetween(1,99).'.jpg';


            // if ($genre == "male") $picture = $picture."men/". $pictureId;
            // else $picture = $picture . "wommen/" . $pictureId;
            $picture = $picture .($genre == "male" ? "men/" : "wommen/") . $pictureId;

            $user->setFirstName($faker->firstname($genre))
                 ->setLastName($faker->lastname)
                 ->setEmail($faker->email)
                 ->setHash($hash)
                 ->setIntroduction($faker->sentence())
                 ->setDescription('<p>' . join('</p><p>', $faker->paragraphs()) . '</p>')
                 ->setPicture($picture)
                 ;
            $manager->persist($user);
            $users[]=$user;
        }

        // Nous gérons les annonces
        for ($i=1; $i<=30; $i++){
            $ad = new Ad();

            $title = $faker->sentence();
            $imageCover = $faker->imageUrl();
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';
            $user = $users[mt_rand(0,count($users)-1)];

            $ad->setTitle($title)
               ->setCoverImage($imageCover)
               ->setIntroduction($introduction)
               ->setContent($content)
               ->setPrice(mt_rand(40,200))
               ->setRooms(mt_rand(1, 5))
               ->setAuthor($user);

            for ($j=1; $j <= mt_rand(2,5); $j++){
                $image = new Image();
                $image->setUrl($faker->imageUrl())
                      ->setCaption($faker->sentence())
                      ->setAd($ad);
                $manager->persist($image);
            }
    
            $manager->persist($ad);
        }

        $manager->flush();
    }
}
