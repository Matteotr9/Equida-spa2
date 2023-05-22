<?php

namespace App\Entity;

use App\Repository\VenteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;


#[ORM\Entity(repositoryClass: VenteRepository::class)]

#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'vente:item']),
        new GetCollection(normalizationContext: ['groups' => 'vente:list'])
    ],
    order: ['nom' => 'ASC', ],
    paginationEnabled: false,
)]
class Vente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['vente:list', 'vente:item'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['vente:list', 'vente:item'])]
    private ?\DateTimeInterface $date_debut = null;
   

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['vente:list', 'vente:item'])]
    private ?\DateTimeInterface $date_fin = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['vente:list', 'vente:item'])]
    private ?\DateTimeInterface $date_vente = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'vente', targetEntity: Lot::class)]
    #[Groups(['vente:list', 'vente:item'])]
    private Collection $lots;

    #[ORM\ManyToOne(inversedBy: 'ventes')]
    #[Groups(['vente:list', 'vente:item'])]
    #[Ignore]
    private ?CategorieDeVente $categorieDeVentes = null;

   

    

    public function __construct()
    {
        $this->lots = new ArrayCollection();
        
       
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(?\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getDateVente(): ?\DateTimeInterface
    {
        return $this->date_vente;
    }

    public function setDateVente(?\DateTimeInterface $date_vente): self
    {
        $this->date_vente = $date_vente;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Lot>
     */
    public function getLots(): Collection
    {
        return $this->lots;
    }

    public function addLot(Lot $lot): self
    {
        if (!$this->lots->contains($lot)) {
            $this->lots->add($lot);
            $lot->setVente($this);
        }

        return $this;
    }

    public function removeLot(Lot $lot): self
    {
        if ($this->lots->removeElement($lot)) {
            // set the owning side to null (unless already changed)
            if ($lot->getVente() === $this) {
                $lot->setVente(null);
            }
        }

        return $this;
    }

    public function getCategorieDeVentes(): ?CategorieDeVente
    {
        return $this->categorieDeVentes;
    }

    public function setCategorieDeVentes(?CategorieDeVente $categorieDeVentes): self
    {
        $this->categorieDeVentes = $categorieDeVentes;

        return $this;
    }

    
   


}
