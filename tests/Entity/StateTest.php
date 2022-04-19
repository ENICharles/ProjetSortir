<?php

namespace App\Tests\Entity;

use App\Entity\State;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class StateTest extends TestCase
{
    public function testId(): void
    {
        $state = new State();
        $this->assertNull($state->getId());
    }

    public function testLabel(): void
    {
        $state = (new State())->setLabel('TestLabel');

        $this->assertEquals("TestLabel", $state->getLabel());
    }

    public function testEvent(EventRepository $er): void
    {
        $ev = $er->findOneBy(['id'=>1]);

        /* vérification que la liste est vide */
        $state = (new State())->addEvent($ev);
        $this->assertEquals(0, $state->getEvents()->count());

        /* vérification que l'ajout est conforme */
        $state->removeEvent($ev);
        $this->assertEquals(1, $state->getEvents()->count());
    }
}
