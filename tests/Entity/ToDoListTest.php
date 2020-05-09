<?php

namespace App\Tests\Entity;
require('vendor/autoload.php');
use App\Entity\ToDoList;
use App\Entity\User;
use App\Service\TodoListService;
use PHPUnit\Framework\TestCase;

class ToDoListTest extends TestCase
{

    public function newUser()
    {

        $user = new User();


        $user->setFirstName('Maxime');
        $user->setLastName('HUET');
        $user->setEmail('maximeh@gmail.com');
        $user->setBirthday(new \DateTime('11-11-1998'));
        $user->setPassword('password1');

        $todolist = new ToDoList();
        $user->setTodolist($todolist);
        $todolist->setAuteur($user);

        return $user;
    }


    public function testAddToDo() {
        $user = $this->newUser();
        $this->assertTrue( $user->getToDolist() instanceof ToDolist );
    }



    public function testAddToDolistAlreadyExist() {
        $user = $this->newUser();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Todo List associer a un autre utilisateur');

        (new TodoListService($user))->createTodolist();
    }


    public function testAddTodolistErrorUserNotValid() {
        $user = $this->newUser();
        $user->setEmail('testerroremail@gmail!com');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(" L'utilisateur n'est pas correct, impossible de creer une ToDoList!");

        (new TodoListService($user))->createTodolist();
    }
}
