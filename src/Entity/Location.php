<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 */
class Location
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
    private $naam;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adres;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $directeur;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Klas", mappedBy="location")
     */
    private $klas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="location")
     */
    private $users;

    public function __construct()
    {
        $this->klas = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNaam(): ?string
    {
        return $this->naam;
    }

    public function setNaam(string $naam): self
    {
        $this->naam = $naam;

        return $this;
    }

    public function getAdres(): ?string
    {
        return $this->adres;
    }

    public function setAdres(string $adres): self
    {
        $this->adres = $adres;

        return $this;
    }

    public function getDirecteur(): ?string
    {
        return $this->directeur;
    }

    public function setDirecteur(string $directeur): self
    {
        $this->directeur = $directeur;

        return $this;
    }

    /**
     * @return Collection|Klas[]
     */
    public function getKlas(): Collection
    {
        return $this->klas;
    }

    public function addKla(Klas $kla): self
    {
        if (!$this->klas->contains($kla)) {
            $this->klas[] = $kla;
            $kla->setLocation($this);
        }

        return $this;
    }

    public function removeKla(Klas $kla): self
    {
        if ($this->klas->contains($kla)) {
            $this->klas->removeElement($kla);
            // set the owning side to null (unless already changed)
            if ($kla->getLocation() === $this) {
                $kla->setLocation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setLocation($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getLocation() === $this) {
                $user->setLocation(null);
            }
        }

        return $this;
    }
}
