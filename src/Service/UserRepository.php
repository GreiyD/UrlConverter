<?php

namespace App\Service;

use App\Entity\UserEntity;
use App\Repository\UserEntityRepository;
use App\Shortener\Interfaces\User\InterfaceUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class UserRepository implements InterfaceUserRepository
{
    /**
     * @var UserEntityRepository
     */
    protected ObjectRepository $repository;

    public function __construct(protected EntityManagerInterface  $em)
    {
        $this->repository = $em->getRepository(UserEntity::class);
    }

    public function checkReg(string $email): ?object
    {
        return $this->repository->findOneBy(['email' => $email]);
    }

    public function logIn(string $email, string $password): ?object
    {
        return $this->repository->findOneBy(['email' => $email, 'password' => $password]);
    }

    public function save(string $nickname, string $email, string $password): bool
    {
        try {
            $entity = new UserEntity($nickname, $email, $password);
            $this->em->persist($entity);
            $this->em->flush();
            $result = true;
        }catch (\Throwable){
            $result = false;
        }
        return $result;
    }

}