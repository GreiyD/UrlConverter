<?php

namespace App\Service;

use App\Entity\UrlCodeEntity;
use App\Entity\UserEntity;
use App\Repository\UrlCodeEntityRepository;
use App\Shortener\Interfaces\UrlConverter\InterfaceConverterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use InvalidArgumentException;

class UrlConverterRepository implements InterfaceConverterRepository
{
    /**
     * @var UrlCodeEntityRepository
     */
    protected ObjectRepository $repository;

    public function __construct(protected EntityManagerInterface  $em)
    {
        $this->repository = $em->getRepository(UrlCodeEntity::class);
    }

    public function saveAll(string $code, string $url, int $userId): bool
    {
        try {
            $user = $this->em->getRepository(UserEntity::class)->findOneBy(['id' => $userId]);

            $entity = new UrlCodeEntity($url, $code, $user);
            $this->em->persist($entity);
            $this->em->flush();
            $result = true;
        }catch (\Throwable){
            $result = false;
        }
        return $result;
    }

    public function getUrl(string $code, int $userId): string
    {
        $entity = $this->repository->findOneBy(['code' => $code, 'user' => $userId]);
        if($entity){
            return $entity->getUrl();
        }else {
            throw new InvalidArgumentException("Не удалось разкодировать, такого Url в базе данных не существует.");
        }
    }

    public function getCode(string $url, int $userId): string
    {
        $entity = $this->repository->findOneBy(['url' => $url, 'user' => $userId]);
        return $entity->getCode();
    }

    public function checkUrlDatabase(string $url, int $userId): ?UrlCodeEntity
    {
        return $this->repository->findOneBy(['url' => $url, 'user' => $userId]);
    }

    public function getAllUrlCode(int $userId): ArrayCollection
    {
        return new ArrayCollection($this->repository->findBy(['user' => $userId]));
    }

    public function increaseTransitionCount(string $url, int $userId)
    {
        $entity = $this->repository->findOneBy(['url' => $url, 'user' => $userId]);

        if ($entity) {
            $currentCount = $entity->getTransitionCount();
            $newCount = $currentCount + 1;

            $entity->setTransitionCount($newCount);

            $this->em->flush();
        } else {
            throw new \InvalidArgumentException("Не удалось найти запись для указанного URL и пользователя.");
        }
    }
}