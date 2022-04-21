<?php

namespace App\Tests\Entity;

use App\Entity\Campus;
use App\Entity\Event;
use App\Entity\Localisation;
use App\Entity\State;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testId(): void
    {
        $event = new Event();
        $this->assertNull($event->getId());
    }

    public function testLabel(): void
    {
        $event = (new Event())->setName('testEvent');

        $this->assertEquals("testEvent", $state->getName());
    }

    public function testDateStart(): void
    {
        $event = (new Event())->setDateStart(new \DateTime());

        $this->assertEquals((new \DateTime()), $event->getDateStart());
    }

    public function testDateLimite(): void
    {
        $event = (new Event())->setInscriptionDateLimit(new \DateTime());

        $this->assertEquals((new \DateTime()), $event->getInscriptionDateLimit());
    }

    public function testNbMax(): void
    {
        $event = (new Event())->setNbMaxInscription(42);

        $this->assertEquals(42, $event->getNbMaxInscription());
    }

    public function testDescription(): void
    {
        $event = (new Event())->setDescription('ma description');

        $this->assertEquals("ma description", $event->getDescription());
    }

    public function testAddUsers(): void
    {
        $event = (new Event())->setName('testEvent');
        $event->addUser(new User());
        $this->assertEquals(1, $event->getUsers()->count());

        $event->removeUser(new User());
        $this->assertEquals(0, $event->getUsers()->count());
    }

    public function testOrganisateur(): void
    {
        $event = (new Event())->setName('testEvent');
        $user = (new User())->setName('bob');

        $event->setOrganisator($user);

        $this->assertEquals($user, $event->getOrganisator());
    }

    public function testCampus(): void
    {
        $event = (new Event())->setName('testEvent');
        $campus = (new Campus())->setName('ENI-Nantes');

        $event->setCampus($campus);

        $this->assertEquals($campus, $event->getCampus());
    }

    public function testLocalisation(): void
    {
        $event = (new Event())->setName('testEvent');
        $local = (new Localisation())->setName('ENI-Nantes');

        $event->setLocalisation($local);

        $this->assertEquals($local, $event->getLocalisation());
    }

    public function testState(): void
    {
        $event = (new Event())->setName('testEvent');
        $state = (new State())->setLabel('testLabel');

        $event->setState($state);

        $this->assertEquals($state, $event->getState());
    }
}
