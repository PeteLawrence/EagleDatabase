<?php

namespace AppBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;

abstract class EagleFixture extends AbstractFixture
{
    /**
     * Takes an array and creates a set of entities
     *
     * @param string $entityClass The type of entity to create
     * @param array  $array       An array representing the entities to be created
     *                            array (
     *                              'currency-GBP' => array(
     *                                'currencycode' => 'GBP',
     *                                'currencyname' => 'GB Pound',
     *                                'decimalplaces' => 2
     *                              ),
     *                              currency-EUR => array(
     *                                'currencycode' => 'EUR',
     *                                'currencyname' => 'Euro',
     *                                'decimalplaces' => 2
     *                              )
     *                            )
     *
     * @return void
     */
    protected function loadFromArray($entityClass, $entitiesArray, $manager)
    {
        foreach ($entitiesArray as $entityRef => $entityArray) {
            //Create a new object of the given class
            $entity = new $entityClass();
            //Set each of the fields on the object
            foreach ($entityArray as $field => $value) {
                $func = 'set' . ucfirst($field);
                $entity->$func($value);
            }
            //Save a reference for this object
            $this->addReference($entityRef, $entity);
            //Persist the entity
            $manager->persist($entity);
        }
        //Flush all changes to the DB
        $manager->flush();
    }
}
