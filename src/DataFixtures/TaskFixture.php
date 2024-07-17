<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TaskFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i=1;$i<=10;$i++)
        {
            $task= new Task();
            $task->setTitle("Task Title : " . $i) ;
            $task->setDescription("Task Description : " . $i) ;
            $manager->persist($task);
        }

        $manager->flush();
    }
}
