<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Notification;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
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

        // https://github.com/fzaninotto/Faker#fakerproviderdatetime
        $faker = Factory::create('FR-fr');
        $users = null;

        $user = new User();
        $user->setEmail('h@boudaoud.fr')
            ->setFirstName('Housni')
            ->setLastName('BOUDAOUD')
            ->setUserName('hboudaoud')
//            ->setPassword('pwd')
            ->setPassword($this->encoder->encodePassword($user, 'pwd'))
            ->setRoles(['ROLE_ADMIN',"ROLE_USER"])
            ->setBirthday(new \DateTime('1979-02-27'));
        $manager->persist($user);

        for ($i = 0; $i < 7; $i++) {
            $user = new User();
                $user->setEmail($faker->email)
                    ->setFirstName($faker->firstName)
                    ->setLastName($faker->lastName)
                    ->setUserName($faker->userName)
                    ->setPassword($this->encoder->encodePassword($user, $faker->password($minLength = 8, $maxlength = 20)))
                    ->setBirthday(new \DateTime($faker->date($format = 'Y-m-d', $max = '-18 years')))
                ;

            $manager->persist($user);
            $users[] = $user;

        }

        for ($i = 0; $i < 5; $i++) {
            $categoryName = $faker->sentence(rand(1, 3));
            $category = new Category();
            $category->setName($categoryName . ' - ' . $i);
            $manager->persist($category);
            $min_price = rand(0, 1000);
            $min_price = rand(0, $min_price);
            for ($j = 0; $j < rand(5, 20); $j++) {
                $dat_update = $faker->dateTimeBetween('-200 days');
                $article = new Article();
                $article->setReference($faker->isbn13)
                    ->setName($faker->sentence(1, 4))
                    ->setCategory($category)
                    ->setPrice(
                        $faker->randomFloat
                        (
                            $nbMaxDecimals = 2,
                            $min = $min_price,
                            $max = rand($min_price+300, $min_price + 500)
                        )
                    )
                    ->setDescription(join('\n', $faker->paragraphs($nb = rand(1, 5), $asText = false)))
                    ->setStockQuantity($faker->numberBetween($min = 10, $max = 9000))
                    ->setStockAlarm($faker->numberBetween($min = 2, $max = 20))
                    ->setCreatedAt(
                        $faker->dateTimeBetween(
                            '-' . ((new \DateTime())->diff($dat_update)->days + rand(0, 100)) . ' days'
                        )
                    );
                if (rand(0, 100) % 27 < 7) {
                    $article->setUpdatedAt($dat_update);
                }
                $manager->persist($article);
                if (rand(1, 100) % 79 < 27) {

                    for ($k = 0; $k < rand(0, 12); $k++) {
                        $notification = new Notification();
                        $notification->setAuthor($users[rand(0, Count($users) - 1)])
                            ->setArticle($article)
                            ->setStarRating((rand(1, 100) % 79 < 27) ? null : rand(1, 5))
                            ->setCreatedAt(
                                $faker->dateTimeBetween(
                                    '-' . ((new \DateTime())->diff($article->getCreatedAt())->days - rand(0, 4)) . ' days'
                                )
                            )->setContent(join('\n', $faker->paragraphs($nb = rand(1, 2), $asText = false)));
                        $manager->persist($notification);
                    }
                }


            }
        }


        $manager->flush();
    }
}
