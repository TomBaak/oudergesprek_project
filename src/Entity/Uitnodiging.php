<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UitnodigingRepository")
 */
class Uitnodiging
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $uitnodigingsCode;

    /**
     * @ORM\Column(type="time")
     */
    public $startTime;

    /**
     * @ORM\Column(type="time")
     */
    public $stopTime;

    /**
     * @ORM\Column(type="date")
     */
    public $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Klas", inversedBy="uitnodiging")
     */
    private $klas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Afspraak", mappedBy="uitnodiging", orphanRemoval=true)
     */
    private $afspraken;

    /**
     * @ORM\Column(type="datetime")
     */
    private $gemaaktOp;

    public function __construct()
    {
        $this->afspraken = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUitnodigingsCode()
    {
        return $this->uitnodigingsCode;
    }

    /**
     * @param mixed $uitnodigingsCode
     */
    public function setUitnodigingsCode($uitnodigingsCode): void
    {
        $this->uitnodigingsCode = $uitnodigingsCode;
    }



    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getStopTime(): ?\DateTimeInterface
    {
        return $this->stopTime;
    }

    public function setStopTime(\DateTimeInterface $stopTime): self
    {
        $this->stopTime = $stopTime;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    public function getDateFormatted()
    {
        setlocale(LC_TIME, 'NL_nl');

        return strftime('%d %B %Y',$this->date->format('U'));
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
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
     * @return Collection|afspraak[]
     */
    public function getAfspraken(): Collection
    {
        return $this->afspraken;
    }

    public function addAfspraken(afspraak $afspraken): self
    {
        if (!$this->afspraken->contains($afspraken)) {
            $this->afspraken[] = $afspraken;
            $afspraken->setUitnodiging($this);
        }

        return $this;
    }

    public function removeAfspraken(afspraak $afspraken): self
    {
        if ($this->afspraken->contains($afspraken)) {
            $this->afspraken->removeElement($afspraken);
            // set the owning side to null (unless already changed)
            if ($afspraken->getUitnodiging() === $this) {
                $afspraken->setUitnodiging(null);
            }
        }

        return $this;
    }

    public function getGemaaktOp(): ?\DateTimeInterface
    {
        return $this->gemaaktOp;
    }

    public function getGemaaktOpFormatted()
    {
        setlocale(LC_TIME, 'NL_nl');

        return strftime('%d %B %Y',$this->date->format('U'));
    }

    public function setGemaaktOp(\DateTimeInterface $gemaaktOp): self
    {
        $this->gemaaktOp = $gemaaktOp;

        return $this;
    }
}
