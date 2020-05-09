<?php

namespace App\Entity;

use App\Repository\ToDoListRepository;
use App\Service\EmailService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Exception\RuntimeException;

/**
 * @ORM\Entity(repositoryClass=ToDoListRepository::class)
 */
class ToDoList
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="toDoList", cascade={"persist", "remove"})
     */
    private $auteur;

    /**
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="toDoList")
     */
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setToDoList($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getToDoList() === $this) {
                $item->setToDoList(null);
            }
        }

        return $this;
    }

    public function canAddItem(Item $item)
    {
        // A FINIR
        // MAX 10 ITEMS
        if($this->items->count() >= 10)
        {
            throw new \RuntimeException('Deja 10 items');
        }
        // MAX 1000 CARAC
        if(strlen($item->getContent()) > 1000)
        {
        throw new \RuntimeException('Vous avez depassé les 1000 caracteres');
        }
        //PAS DE DOUBLONS
        foreach($this->items as $doublon){
            if($doublon->getName() == $item->getName()){
                throw new \RuntimeException('Items deja existant '. $item->getName() );
            }
        }
        //EMAIL SEND
         if(!EmailService::send($this->getAuteur())){
           throw new \RuntimeException('Probleme email');
         }
        //TEMPS ATTENTE 30min
        $finalItem = $this->items->last();
        if($finalItem && $finalItem->getCreatedAt()->modify('+30 minutes')->getTimestamp() <= (new \DateTime('now'))->getTimestamp()){
            throw new \RuntimeException('Vous devez attendre 30min');
        }
        return $item;
    }

}
