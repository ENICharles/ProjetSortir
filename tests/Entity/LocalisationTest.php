<?php

namespace App\Tests\Entity;

use App\Entity\Localisation;
use App\Repository\CityRepository;
use App\Repository\EventRepository;
use PHPUnit\Framework\TestCase;

class LocalisationTest extends TestCase
{
    public function testId(): void
    {
        $localisation = new Localisation();
        $this->assertNull($localisation->getId());
    }

    public function testName(): void
    {
        $localisation = (new Localisation())->setName('TestName');

        $this->assertEquals("TestName", $localisation->getName());
    }

    public function testStreet(): void
    {
        $localisation = (new Localisation())->setStreet('ici');

        $this->assertEquals("ici", $localisation->getStreet());
    }

    public function testLatitude(): void
    {
        $localisation = (new Localisation())->setLatitude('321.654');

        $this->assertEquals(321.654, $localisation->getLatitude());
    }

    public function testLongitude(): void
    {
        $localisation = (new Localisation())->setLongitude('987.654');

        $this->assertEquals(987.654, $localisation->getLongitude());
    }

    public function testEvent(EventRepository $er): void
    {
        $ev = $er->findOneBy(['id'=>1]);

        /* vérification que la liste est vide */
        $localisation = (new Localisation())->addEvent($ev);
        $this->assertEquals(0, $localisation->getEvents()->count());

        /* vérification que l'ajout est conforme */
        $localisation->removeEvent($ev);
        $this->assertEquals(1, $localisation->getEvents()->count());
    }

    public function testCity(CityRepository $cr): void
    {
        $ct = $cr->findOneBy(['id'=>1]);

        /* vérification que la liste est vide */
        $localisation = (new Localisation())->setCity($ct);
        $this->assertObjectEquals($ct, $localisation->getCity());
    }
}
