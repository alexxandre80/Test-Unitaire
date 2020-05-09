<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Item;
use App\Entity\Todolist;

class TodoListService {

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getToDolist() {
        return $this->user->getToDolist();
    }

    public function createToDolist() {

        if( !$this->user->isValid() ){
            throw new \RuntimeException(" L'utilisateur n'est pas correct, impossible de creer une ToDoList!");
        }

        if( $this->user->getToDolist() instanceof ToDolist){
            throw new \RuntimeException("Todo List associer a un autre utilisateur");
        }
    }

    public function addItem(Item $item) {

        $todo = $this->user->getToDolist();

        if(!$todo instanceof ToDolist){
            throw new \RuntimeException("L'utilisateur n'a pas de ToDo list");
        }


        $last = $todo->getItems()->last();
        if($last instanceof Item && round(((new \DateTime)->getTimestamp() - $last->getCreatedAt()->getTimestamp()) / 60 ) < 30){
            throw new \RuntimeException('Vous devez attendre 30min');
        }

        if($item->getContent() === null || strlen($item->getContent()) === 0){
            throw new \RuntimeException("L'item est vide");
        }

        if(strlen($item->getContent()) > 1000){
            throw new \RuntimeException('Vous avez depassé les 1000 caracteres');
        }
        if($todo->getItems()->count() >= 10) {
            throw new \RuntimeException("Deja 10 items");
        }



        if($todo->canAddItem($item)){
            $todo->addItem($item);
            return true;
        }

        return false;
    }

}