<?php

namespace App\Entity;

use App\Repository\LogPortiquesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogPortiquesRepository::class)]
#[ORM\Table(name: "log_portiques")]
class LogPortiques
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column]
    private ?\DateTime $time = null;

    #[ORM\Column]
    private ?int $pin = null;

    #[ORM\Column(length: 10)]
    private ?string $card_no = null;

    #[ORM\Column]
    private ?int $device_id = null;

    #[ORM\Column(length: 19)]
    private ?string $device_sn = null;

    #[ORM\Column(length: 30)]
    private ?string $device_name = null;

    #[ORM\Column]
    private ?int $verified = null;

    #[ORM\Column]
    private ?int $state = null;

    #[ORM\Column]
    private ?int $event_point_id = null;

    #[ORM\Column(length: 15)]
    private ?string $event_point_name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTime(): ?\DateTime
    {
        return $this->time;
    }

    public function setTime(\DateTime $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getPin(): ?int
    {
        return $this->pin;
    }

    public function setPin(int $pin): static
    {
        $this->pin = $pin;

        return $this;
    }

    public function getCardNo(): ?string
    {
        return $this->card_no;
    }

    public function setCardNo(string $card_no): static
    {
        $this->card_no = $card_no;

        return $this;
    }

    public function getDeviceId(): ?int
    {
        return $this->device_id;
    }

    public function setDeviceId(int $device_id): static
    {
        $this->device_id = $device_id;

        return $this;
    }

    public function getDeviceSn(): ?string
    {
        return $this->device_sn;
    }

    public function setDeviceSn(string $device_sn): static
    {
        $this->device_sn = $device_sn;

        return $this;
    }

    public function getDeviceName(): ?string
    {
        return $this->device_name;
    }

    public function setDeviceName(string $device_name): static
    {
        $this->device_name = $device_name;

        return $this;
    }

    public function getVerified(): ?int
    {
        return $this->verified;
    }

    public function setVerified(int $verified): static
    {
        $this->verified = $verified;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getEventPointId(): ?int
    {
        return $this->event_point_id;
    }

    public function setEventPointId(int $event_point_id): static
    {
        $this->event_point_id = $event_point_id;

        return $this;
    }

    public function getEventPointName(): ?string
    {
        return $this->event_point_name;
    }

    public function setEventPointName(string $event_point_name): static
    {
        $this->event_point_name = $event_point_name;

        return $this;
    }
}
