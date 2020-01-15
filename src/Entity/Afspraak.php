<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AfspraakRepository")
 */
class Afspraak
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $studentNumber;

    /**
     * @ORM\Column(type="boolean")
     */
    private $withParents;

    /**
     * @ORM\Column(type="time")
     */
    private $time;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phoneNumber;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Uitnodiging", inversedBy="afspraken")
     */
    private $uitnodiging;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudentNumber(): ?string
    {
        return $this->studentNumber;
    }

    public function setStudentNumber(string $studentNumber): self
    {
        $this->studentNumber = $studentNumber;

        return $this;
    }

    public function getWithParents(): ?bool
    {
        return $this->withParents;
    }

    public function setWithParents(bool $withParents): self
    {
        $this->withParents = $withParents;

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

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getUitnodiging(): ?Uitnodiging
    {
        return $this->uitnodiging;
    }

    public function setUitnodiging(?Uitnodiging $uitnodiging): self
    {
        $this->uitnodiging = $uitnodiging;

        return $this;
    }
}
