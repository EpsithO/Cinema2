<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nbPlaceReservation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $DateRéservation = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\ManyToOne(targetEntity: Seance::class, inversedBy: 'reservations')]
    private Collection $Seance;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reservations')]
    private Collection $users;

    public function __construct()
    {
        $this->Seance = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbPlaceReservation(): ?int
    {
        return $this->nbPlaceReservation;
    }

    public function setNbPlaceReservation(int $nbPlaceReservation): static
    {
        $this->nbPlaceReservation = $nbPlaceReservation;

        return $this;
    }

    public function getDateRéservation(): ?\DateTimeInterface
    {
        return $this->DateRéservation;
    }

    public function setDateRéservation(\DateTimeInterface $DateRéservation): static
    {
        $this->DateRéservation = $DateRéservation;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * @return Collection<int, Seance>
     */
    public function getSeance(): Collection
    {
        return $this->Seance;
    }

    public function addSeance(Seance $seance): static
    {
        if (!$this->Seance->contains($seance)) {
            $this->Seance->add($seance);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): static
    {
        $this->Seance->removeElement($seance);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addReservation($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeReservation($this);
        }

        return $this;
    }
}
