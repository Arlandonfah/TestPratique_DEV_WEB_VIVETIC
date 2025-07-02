<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\LogPortiqueRepository")]
class LogPortique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'datetime')]
    private $time;

    #[ORM\Column(type: 'string', length: 50)]
    private $pin;

    #[ORM\Column(type: 'string', length: 50)]
    private $cardNo;

    #[ORM\Column(type: 'string', length: 50)]
    private $deviceId;

    #[ORM\Column(type: 'string', length: 50)]
    private $deviceSn;

    #[ORM\Column(type: 'string', length: 255)]
    private $deviceName;

    #[ORM\Column(type: 'boolean')]
    private $verified;

    #[ORM\Column(type: 'string', length: 50)]
    private $state;

    #[ORM\Column(type: 'integer')]
    private $eventPointId;

    #[ORM\Column(type: 'string', length: 50)]
    private $eventPointName;

    // Getters et Setters pour toutes les propriétés
    // ...
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;
        return $this;
    }

    public function getPin(): ?string
    {
        return $this->pin;
    }

    public function setPin(string $pin): self
    {
        $this->pin = $pin;
        return $this;
    }

    public function getCardNo(): ?string
    {
        return $this->cardNo;
    }

    public function setCardNo(string $cardNo): self
    {
        $this->cardNo = $cardNo;
        return $this;
    }

    public function getDeviceId(): ?string
    {
        return $this->deviceId;
    }

    public function setDeviceId(string $deviceId): self
    {
        $this->deviceId = $deviceId;
        return $this;
    }

    public function getDeviceSn(): ?string
    {
        return $this->deviceSn;
    }

    public function setDeviceSn(string $deviceSn): self
    {
        $this->deviceSn = $deviceSn;
        return $this;
    }

    public function getDeviceName(): ?string
    {
        return $this->deviceName;
    }

    public function setDeviceName(string $deviceName): self
    {
        $this->deviceName = $deviceName;
        return $this;
    }

    public function isVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): self
    {
        $this->verified = $verified;
        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;
        return $this;
    }

    public function getEventPointId(): ?int
    {
        return $this->eventPointId;
    }

    public function setEventPointId(int $eventPointId): self
    {
        $this->eventPointId = $eventPointId;
        return $this;
    }

    public function getEventPointName(): ?string
    {
        return $this->eventPointName;
    }

    public function setEventPointName(string $eventPointName): self
    {
        $this->eventPointName = $eventPointName;
        return $this;
    }

    public function __toString(): string
    {
        return $this->name . ' - ' . $this->time->format('Y-m-d H:i:s');
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'time' => $this->getTime()->format('Y-m-d H:i:s'),
            'pin' => $this->getPin(),
            'cardNo' => $this->getCardNo(),
            'deviceId' => $this->getDeviceId(),
            'deviceSn' => $this->getDeviceSn(),
            'deviceName' => $this->getDeviceName(),
            'verified' => $this->isVerified(),
            'state' => $this->getState(),
            'eventPointId' => $this->getEventPointId(),
            'eventPointName' => $this->getEventPointName(),
        ];
    }
}