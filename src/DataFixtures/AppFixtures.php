<?php

namespace App\DataFixtures;

use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $type_contrat= array("CDI","CDD","Stage","Alternance");
        for($i=0;$i<count($type_contrat);$i++){
            $type=new Type();
            $type->setNom($type_contrat[$i]);
            $manager->persist($type);
        }
        $manager->flush();
    }
}
