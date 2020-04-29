<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');

        for ($i = 1; $i < 30; $i++){
            $ad = new Ad();
            $title = $faker->sentence();
            $ad->setTitle($title)
                ->setCoverImage($faker->imageUrl(1000, 350))
                ->setIntroduction($faker->paragraph(random_int(1, 3)))
                ->setContent('<p>' . join('</p><p>', $faker->paragraphs(random_int(2, 6) )) . '</p>')
                ->setPrice(random_int(20, 350))
                ->setRooms(random_int(1, 5));

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
