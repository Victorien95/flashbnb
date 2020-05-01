<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {

        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        // Users
        $users = [];
        $genres = ['male', 'female'];
        for ($i = 1; $i<=20; $i++){
            $user = new User();

            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';
            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstName($genre))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence)
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(random_int(2, 4) )) . '</p>')
                ->setHash($hash)
                ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user;
        }

        // Annonces
        for ($i = 1; $i < 30; $i++){
            $ad = new Ad();
            $title = $faker->sentence();
            $user = $users[mt_rand(0, count($users) - 1)];
            $ad->setTitle($title)
                ->setCoverImage($faker->imageUrl(1000, 350))
                ->setIntroduction($faker->paragraph(random_int(1, 3)))
                ->setContent('<p>' . join('</p><p>', $faker->paragraphs(random_int(2, 6) )) . '</p>')
                ->setPrice(random_int(20, 350))
                ->setRooms(random_int(1, 5))
                ->setAuthor($user);

            for($j = 1; $j <= mt_rand(2, 5); $j++){
                $image = new Image();
                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence)
                    ->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
        }

        $manager->flush();
    }
}