<?php

namespace App\Tests\Entity;
require('vendor/autoload.php');
use App\Entity\Item;
use App\Entity\User;
use App\Entity\ToDoList;
use App\Service\TodoListService;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public function newItem() {

        $user = new User();
        $todolist = new ToDolist();

        $user->setTodolist($todolist);
        $todolist->setAuteur($user);

        return new TodoListService($user);
    }

    public function testAddItem() {
        $todolistService = $this->newItem();
        $item = new Item('test', 'Test Test');

        $this->assertTrue( $todolistService->addItem($item) );
    }

    public function testContentLenght(){
        $item = new Item('test', 'Test ');
        //Repeat 1001 Time the word "A"
        $item->setContent(str_repeat("Test", 300));
        $this->assertFalse($item->isValid());
    }

    public function testEmail() {
        $todolistService = $this->newItem();
        $item = new Item('test', 'Test Test');

        $this->assertTrue($todolistService->addItem($item) );
    }

  /*  public function testAdd11Items() {
        $todolistService = $this->newItem();
        $item = new Item('Test', 'Test');

        for ($i = 0; $i < 8; $i++){
            $item = new Item('test', 'Test Test');
            $todolistService->getTodolist()->addItem($item);
        }

        $this->expectExceptionMessage('Plus de 10 items');
        $todolistService->addItem($item);
    }*/

    public function testItemDoublons() {
        $todolistService = $this->newItem();
        $item1 = new Item('test1', 'TEST1');
        $item1->setDateCreated( (new \DateTime())->modify('-60 mins') );

        $item2 = new Item('test12', 'TEST2');
        $item2->setDateCreated( (new \DateTime())->modify('-10 mins') );
        $todolistService->addItem($item1);

        $this->expectExceptionMessage('Doublons');
        $todolistService->addItem($item2);
    }
}
