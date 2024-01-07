<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'url_codes')]
class UrlCodeEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;
    #[ORM\Column(length: 255)]
    private string $url;
    #[ORM\Column(length: 255)]
    private string $code;
    #[ORM\ManyToOne(targetEntity: UserEntity::class, inversedBy: 'urlCodes')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private $user;
    #[ORM\Column()]
    private int $transitionCount;

    public function __construct(string $url = '', string $code = '', $user = '', int $transitionCount = 0)
    {
        $this->url = $url;
        $this->code = $code;
        $this->user = $user;
        $this->transitionCount = $transitionCount;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getUser(): mixed
    {
        return $this->user;
    }

    public function setUser(mixed $user): void
    {
        $this->user = $user;
    }

    public function getTransitionCount(): int
    {
        return $this->transitionCount;
    }

    public function setTransitionCount(int $transitionCount): void
    {
        $this->transitionCount = $transitionCount;
    }

}
