<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\User\Dto\Create;
use App\Service\User\Dto\Update;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    public function __construct(
        private UserRepository $repo,
        private EntityManagerInterface $em
    ) {}


    /**
     * @return User[]       All Users entities array
     */
    public function all(): array
    {
        return $this->repo->findAll();
    }

    /**
     * @param int $id       The ID of user you are looking for
     * @return User|null    The entity of user or null
     */
    public function getById(int $id): ?User
    {
        return $this->repo->findOneBy(['id' => $id]);
    }

    /**
     * @param Create $dto   Data-transfer object to create a user
     * @return User         The entity of created user
     * @throws \Exception
     */
    public function create(Create $dto): User
    {
        $user = new User();
        $user->setBirthday(new \DateTimeImmutable($dto->birthday));
        $user->setCreatedAt(new \DateTimeImmutable('NOW'));
        $this->setGeneral($dto, $user);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    /**
     * @param Update $dto   Data-transfer object to update a user
     * @param User $user    The entity of user being updated
     * @return User         The entity of updated user
     */
    public function update(Update $dto, User $user)
    {
        $this->setGeneral($dto, $user);
        $user->setUpdatedAt(new \DateTime('NOW'));

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    /**
     * @param User $user    The entity of user being deleted
     * @return void
     */
    public function delete(User $user): void
    {
        $this->em->remove($user);
        $this->em->flush();
    }

    /**
     * @param $dto          Data-transfer object to create or update a user
     * @param User $user    The entity of user being created or updated
     * @return User         The entity of created or updated user
     */
    private function setGeneral($dto, User $user): User
    {
        $user->setName($dto->name);
        $user->setEmail($dto->email);
        $user->setPhone($dto->phone);
        $user->setSex($dto->sex);
        $user->setAge($this->ageСalc($user));

        return $user;
    }

    /**
     * @param User $user    The entity of user for age calculation
     * @return int          User age
     */
    private function ageСalc(User $user): int
    {
        $now = (new \DateTime('NOW'))->getTimestamp();
        $ageInt = date('Ymd', $now) - date('Ymd', $user->getBirthday()->getTimestamp());
        $age = (string) $ageInt;
        $age = (int) substr($age, 0, -4);

        return $age;
    }
}
