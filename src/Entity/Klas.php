<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\KlasRepository")
 */
class Klas
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="array")
     */
    private $leerlingen = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="klas")
     */
    private $slb;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Uitnodiging", mappedBy="klas")
     */
    private $uitnodiging;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $naam;

    public function __construct()
    {
        $this->uitnodiging = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLeerlingen(): ?array
	{
  
		$decoded_leerlingen = [];
		
		for($i = 0; $i < count($this->leerlingen); $i++){
			
			array_push($decoded_leerlingen, json_decode($this->leerlingen[$i]));
			
		}
		
        return $decoded_leerlingen;
    }
	
	public function addLeerling($leerling)
	{
		array_push($this->leerlingen, $leerling);
	}
	
	
	public function setLeerlingen(array $leerlingen): self
    {
        $this->leerlingen = $leerlingen;

        return $this;
    }

    public function getSlb(): ?User
    {
        return $this->slb;
    }

    public function setSlb(?User $slb): self
    {
        $this->slb = $slb;

        return $this;
    }

    /**
     * @return Collection|uitnodiging[]
     */
    public function getUitnodiging(): Collection
    {
        return $this->uitnodiging;
    }

    public function addUitnodiging(uitnodiging $uitnodiging): self
    {
        if (!$this->uitnodiging->contains($uitnodiging)) {
            $this->uitnodiging[] = $uitnodiging;
            $uitnodiging->setKlas($this);
        }

        return $this;
    }

    public function removeUitnodiging(uitnodiging $uitnodiging): self
    {
        if ($this->uitnodiging->contains($uitnodiging)) {
            $this->uitnodiging->removeElement($uitnodiging);
            // set the owning side to null (unless already changed)
            if ($uitnodiging->getKlas() === $this) {
                $uitnodiging->setKlas(null);
            }
        }

        return $this;
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
}
