<?php

namespace App\Entity;

use App\Repository\StyleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Artist;
use App\Entity\Producteur;
use App\Entity\Music;

#[ORM\Entity(repositoryClass: StyleRepository::class)]
class Style
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToMany(mappedBy: 'styles', targetEntity: Artist::class)]
    private Collection $artists;

    #[ORM\ManyToMany(mappedBy: 'styles', targetEntity: Producteur::class)]
    private Collection $producers;

    #[ORM\ManyToMany(mappedBy: 'styles', targetEntity: Music::class)]
    private Collection $musics;

    public function __construct()
    {
        $this->artists = new ArrayCollection();
        $this->producers = new ArrayCollection();
        $this->musics = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Artist>
     */
    public function getArtists(): Collection
    {
        return $this->artists;
    }

    public function addArtist(Artist $artist): static
    {
        if (!$this->artists->contains($artist)) {
            $this->artists->add($artist);
            $artist->addStyle($this);
        }

        return $this;
    }

    public function removeArtist(Artist $artist): static
    {
        if ($this->artists->removeElement($artist)) {
            $artist->removeStyle($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Producteur>
     */
    public function getProducers(): Collection
    {
        return $this->producers;
    }

    public function addProducer(Producteur $producer): static
    {
        if (!$this->producers->contains($producer)) {
            $this->producers->add($producer);
            $producer->addStyle($this);
        }

        return $this;
    }

    public function removeProducer(Producteur $producer): static
    {
        if ($this->producers->removeElement($producer)) {
            $producer->removeStyle($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Music>
     */
    public function getMusics(): Collection
    {
        return $this->musics;
    }

    public function addMusic(Music $music): static
    {
        if (!$this->musics->contains($music)) {
            $this->musics->add($music);
            $music->addStyle($this);
        }

        return $this;
    }

    public function removeMusic(Music $music): static
    {
        if ($this->musics->removeElement($music)) {
            $music->removeStyle($this);
        }

        return $this;
    }
}
