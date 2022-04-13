<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUser(): void
    {
       // $this->assertTrue(true);
        $user = (new User())
        ->setName("Martin")
        ->setFirstname('Gertrude')
        ->setUsername('Gigi')
        ->setPassword('000000')
       // ->setCampus('Eni')
        ->setEmail('gigi@eni.fr')
        ->setRoles([])
        ->setIsActive(true)
        ->setIsAdmin(false)
        ->setPhone('0625456325');

        $this->assertEquals("Martin", $user->getName());
        $this->assertNotEquals("Dupont", $user->getName());

        $this->assertEquals("Gertrude", $user->getFirstname());
        $this->assertNotEquals("Marie", $user->getFirstname());

        $this->assertEquals("Gigi", $user->getUsername());
        $this->assertNotEquals("bob49", $user->getUsername());

        $this->assertEquals("000000", $user->getPassword());
        $this->assertNotEquals("zjdioazjj", $user->getPassword());

        $this->assertEquals("Eni", $user->getCampus());
        $this->assertNotEquals("Fac", $user->getCampus());

        $this->assertEquals("gigi@eni.fr", $user->getEmail());
        $this->assertNotEquals("bob@eni.fr", $user->getEmail());

        $this->assertEquals([], $user->getRoles());
        $this->assertNotEquals(1, $user->getRoles());

        $this->assertEquals(true, $user->getIsActive());
        $this->assertNotEquals(false, $user->getIsActive());

        $this->assertEquals(false, $user->getIsAdmin());
        $this->assertNotEquals(true, $user->getIsAdmin());

        $this->assertEquals("0625456325", $user->getPhone());
        $this->assertNotEquals("0000000000", $user->getPhone());

    }
}
