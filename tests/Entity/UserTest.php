<?php

namespace App\Tests\Entity;

use App\Entity\Campus;
use App\Entity\Event;
use App\Entity\User;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testId(): void
    {
        $user = new User();

        $this->assertNull($user->getId());
    }

    public function testName(): void
    {
        $user = (new User())->setName("Martin");

        $this->assertEquals("Martin", $user->getName());
    }

    public function testFirstname(): void
    {
        $user = (new User())->setFirstname('Gertrude');

        $this->assertEquals("Gertrude", $user->getFirstname());
    }

    public function testUsername(): void
    {
        $user = (new User())->setUsername('GIGI');

        $this->assertEquals("GIGI", $user->getUsername());
    }

    public function testPassword(): void
    {
        $user = (new User())->setPassword('023456');

        $this->assertEquals("023456", $user->getPassword());;
    }

    public function testCampus(): void
    {
        $campus = (new Campus())->setName('campusTest');

        $user = (new User())->setCampus($campus);

        $this->assertEquals($campus, $user->getCampus());
    }

    public function testEmail(): void
    {
        $user = (new User())->setEmail('gigi@eni.fr');

        $this->assertEquals("gigi@eni.fr", $user->getEmail());
    }

    public function testIdentifier(): void
    {
        $user = (new User())->setEmail('gigi@eni.fr');

        $this->assertEquals("gigi@eni.fr", $user->getUserIdentifier());
    }

    public function testRoles(): void
    {
        $user = (new User())->setRoles(['USER_ROLE']);

        $this->assertEquals("USER_ROLE", ($user->getRoles())[0]);
    }

    public function testActive(): void
    {
        $user = (new User())->setIsActive(true);

        $this->assertEquals(true, $user->getIsActive());
    }

    public function testAdmin(): void
    {
        $user = (new User())->setIsAdmin(false);

        $this->assertEquals(false, $user->getIsAdmin());
    }

    public function testPhone(): void
    {
        $user = (new User())->setPhone('0123456978');

        $this->assertEquals('0123456978', $user->getPhone());
    }

    public function testEventAdd(): void
    {
        $ev = (new Event())->setName('EventTest');

        $user = new User();

        /* pas d'évènement */
        $this->assertEquals(0, $user->getEvents()->count());

        /* ajout de l'évènement */
        $user->addEvent($ev);

        /* validation de l'ajout */
        $this->assertEquals(1, $user->getEvents()->count());
    }

    public function testEventRemove(): void
    {
        $ev = (new Event())->setName('EventTest');

        $user = new User();

        $user->addEvent($ev);
        $this->assertEquals(1, $user->getEvents()->count());
        $user->removeEvent($ev);
        $this->assertEquals(0, $user->getEvents()->count());
    }


}
