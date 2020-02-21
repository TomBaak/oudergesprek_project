<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StudentRepository")
 */
class Student
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Klas", inversedBy="students")
     */
    private $klas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Afspraak", mappedBy="student", orphanRemoval=true)
     */
    private $afspraken;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tussenVoegsel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $voornaam;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $achternaam;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $emailAdres;

    public function __construct()
    {
        $this->afspraken = new ArrayCollection();
        setlocale(LC_TIME, 'NL_nl');
    }
	
	public function getNaam(): ?string
	{
		
		if(strlen($this->tussenVoegsel) > 0){
			return $this->voornaam . ' ' . $this->tussenVoegsel . ' ' . $this->achternaam;
		}else{
			return $this->voornaam . ' ' . $this->achternaam;
		}
		
		
	}
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKlas(): ?Klas
    {
        return $this->klas;
    }

    public function setKlas(?Klas $klas): self
    {
        $this->klas = $klas;

        return $this;
    }

    /**
     * @return Collection|Afspraak[]
     */
    public function getAfspraken(): Collection
    {
        return $this->afspraken;
    }

    public function addAfspraken(Afspraak $afspraken): self
    {
        if (!$this->afspraken->contains($afspraken)) {
            $this->afspraken[] = $afspraken;
            $afspraken->setStudent($this);
        }

        return $this;
    }

    public function removeAfspraken(Afspraak $afspraken): self
    {
        if ($this->afspraken->contains($afspraken)) {
            $this->afspraken->removeElement($afspraken);
            // set the owning side to null (unless already changed)
            if ($afspraken->getStudent() === $this) {
                $afspraken->setStudent(null);
            }
        }

        return $this;
    }

    public function getTussenVoegsel(): ?string
    {
        return $this->tussenVoegsel;
    }

    public function setTussenVoegsel(string $tussenVoegsel): self
    {
        $this->tussenVoegsel = $tussenVoegsel;

        return $this;
    }

    public function getVoornaam(): ?string
    {
        return $this->voornaam;
    }

    public function setVoornaam(string $voornaam): self
    {
        $this->voornaam = $voornaam;

        return $this;
    }

    public function getAchternaam(): ?string
    {
        return $this->achternaam;
    }

    public function setAchternaam(string $achternaam): self
    {
        $this->achternaam = $achternaam;

        return $this;
    }

    public function getEmailAdres(): ?string
    {
        return $this->emailAdres;
    }

    public function setEmailAdres(string $emailAdres): self
    {
        $this->emailAdres = $emailAdres;

        return $this;
    }
}
