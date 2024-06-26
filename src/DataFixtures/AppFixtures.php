<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Service\User\Sex;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=1;$i<=9;$i++) {
            $user = new User();
            $user->setName("user$i");
            $user->setPhone($this->randomPhone());
            $user->setEmail("email$i@mail.ru");
            $dates = $this->randomDates();
            $user->setBirthday($dates['birthday']);
            $user->setAge($dates['age']);
            $user->setCreatedAt(new \DateTimeImmutable('NOW'));
            $user->setSex(Sex::randomCaseAsString());
            $user->setUpdatedAt(null);
            $manager->persist($user);
        }

        $manager->flush();
    }

    private function randomPhone(): string
    {
        $phone = '+';
        for ($i=1;$i<=10;$i++) {
            $phone .= (string) $i;
        }

        return $phone;
    }

    private function randomDates(): array
    {
        $date = new \DateTime('NOW');
        $currentTimestamp = $date->getTimestamp();
        $timestamp = random_int(100000000, $currentTimestamp);
        $age = date('Ymd', $currentTimestamp) - date('Ymd', $timestamp);
        $dates['age'] = substr($age, 0, -4);
        $dates['birthday'] = new \DateTimeImmutable(date("Y-m-d", $timestamp));

        return $dates;
    }
}
