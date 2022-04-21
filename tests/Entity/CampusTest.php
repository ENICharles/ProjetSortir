<?php

namespace App\Tests\Entity;

use App\Entity\Campus;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class CampusTest extends TestCase
{
    public function testId(): void
    {
        $campus = new Campus();

        $this->assertNull($campus->getId());
    }

    public function testName(): void
    {
        $campus = (new Campus())->setName("ENI-NANTES");

        $this->assertEquals("ENI-NANTES", $campus->getName());
    }

    public function testCampusAdd(): void
    {
        $user = (new User())->setName('ENI');

        $campus = new Campus();

        /* pas de user */
        $this->assertEquals(0, $campus->getUsers()->count());

        /* ajout de l'user */
        $campus->addEvent($user);

        /* validation de l'ajout */
        $this->assertEquals(1, $campus->getUsers()->count());
    }
}
