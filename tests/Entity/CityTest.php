<?php

namespace App\Tests\Entity;

use App\Entity\City;
use App\Entity\Localisation;
use App\Repository\LocalisationRepository;
use PHPUnit\Framework\TestCase;

class CityTest extends TestCase
{
    public function testId(): void
    {
        $city = new City();

        $this->assertNull($city->getId());
    }

    public function testName(): void
    {
        $city = (new City())->setName("Martin");

        $this->assertEquals("Martin", $city->getName());
    }

    public function testPostCode(): void
    {
        $city = (new City())->setPostcode("654321");

        $this->assertEquals("654321", $city->getPostcode());
    }

    public function testLocalisation(LocalisationRepository $local): void
    {
        $local = $local->findOneBy(['id'=>1]);

        $city = (new City())->addLocalisation($local);

        $this->assertObjectEquals($local, $city->getLocalisations());
    }

    public function testRemoveLocalisation(): void
    {
        $local = (new Localisation())->setName('testLocalisation');

        $city = (new City())->addLocalisation($local);
        $this->assertEquals(0, $city->getLocalisations()->count());

        $city->removeLocalisation($local);

        $this->assertFalse($city->getLocalisations()->contains($local));
    }
}
