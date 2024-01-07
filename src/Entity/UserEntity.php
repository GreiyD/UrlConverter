<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity()]
#[ORM\Table(name: 'user')]
class UserEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;
    #[ORM\Column(length: 40)]
    private string $nickname;
    #[ORM\Column(length: 255)]
    private string $email;
    #[ORM\Column(length: 10)]
    private string $password;
    #[ORM\OneToMany(targetEntity: UrlCodeEntity::class, mappedBy: 'user')]
    private $urlCodes;

    public function __construct(string $nickname = '', string $email = '', string $password = '')
    {
        $this->nickname = $nickname;
        $this->email = $email;
        $this->password = $password;
        $this->urlCodes = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getUrlCodes(): ArrayCollection
    {
        return $this->urlCodes;
    }

    public function setUrlCodes(ArrayCollection $urlCodes): void
    {
        $this->urlCodes = $urlCodes;
    }

}
