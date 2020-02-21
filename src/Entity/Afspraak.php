<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AfspraakRepository")
 */
class Afspraak
{

    public function __construct()
    {
        setlocale(LC_TIME, 'NL_nl');
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $metOuders;

    /**
     * @ORM\Column(type="time")
     */
    private $tijd;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telefoonNummer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Uitnodiging", inversedBy="afspraken")
     */
    private $uitnodiging;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Student", inversedBy="afspraken")
     */
    private $student;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getMetOuders()
    {
        return $this->metOuders;
    }

    /**
     * @param mixed $metOuders
     */
    public function setMetOuders($metOuders): void
    {
        $this->metOuders = $metOuders;
    }

    /**
     * @return mixed
     */
    public function getTijd()
    {
        return $this->tijd;
    }

    /**
     * @param mixed $tijd
     */
    public function setTijd($tijd): void
    {
        $this->tijd = $tijd;
    }

    /**
     * @return mixed
     */
    public function getTelefoonNummer()
    {
        return $this->telefoonNummer;
    }

    /**
     * @param mixed $telefoonNummer
     */
    public function setTelefoonNummer($telefoonNummer): void
    {
        $this->telefoonNummer = $telefoonNummer;
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

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }
}
