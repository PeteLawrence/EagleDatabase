<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class LoadMembersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
        ->setName('eagledb:load:members')
        ->addArgument('file', InputArgument::REQUIRED, 'The name of the file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $fh = fopen($input->getArgument('file'), 'r');

        //Read in the first line of headers
        fgetcsv($fh);

        while (($data = fgetcsv($fh, 1000, ",")) !== false) {
            printf('%s %s', $data[1], $data[2]);
            $person = new \AppBundle\Entity\Person;
            $person->setForename($data[1]);
            $person->setSurname($data[2]);
            $person->setEmail($data[12]);
            $person->setAdmin(false);
            $person->setPassword(null);
            if ($data[13] != 'NULL') {
                $person->setDob(new \DateTime($data[13]));
            }
            $person->setAddr1($data[4]);
            $person->setAddr2($data[5]);
            $person->setAddr2($data[6]);
            $person->setTown($data[7]);
            $person->setCounty($data[8]);
            $person->setPostcode($data[9]);
            $person->setTelephone($data[10]);
            $person->setMobile($data[11]);
            $person->setDisability(false);
            $person->setNotes($data[17]);

            $person->setGender($data[49]);

            $person->setIsActive(true);

            $person->setNextOfKinName($data[20]);
            $person->setNextOfKinContactDetails($data[21]);
            $person->setNextOfKinRelation($data[22]);

            $person->setNotes($data[17]);

            $person->setPassword('X'); //set to X as can't be null

            $em->persist($person);
        }

        $em->flush();

        fclose($fh);
    }
}
