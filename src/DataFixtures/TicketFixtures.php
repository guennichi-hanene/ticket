<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TicketFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i=0 ; $i<30 ; $i++) {
            $ticket = new Ticket();
            $ticket->setTitre($faker->titre);
            $ticket->setPersonne($faker->personne);
            $ticket->setStatut($faker->statut);
            $ticket->setDescription($faker->description);
            $ticket->setDate($faker->date);
            $manager->persist($ticket);
        $manager->flush();
        }
    }
}
