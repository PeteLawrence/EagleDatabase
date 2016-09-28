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
            $person->setEmail($data[11]);
            $person->setAdmin(false);
            $person->setPassword(null);
            $person->setGender(null);
            if ($data[12] != 'NULL') {
                $person->setDob(new \DateTime($data[12]));
            }
            $person->setAddr1($data[4]);
            $person->setAddr2($data[5]);
            $person->setTown($data[6]);
            $person->setCounty($data[7]);
            $person->setPostcode($data[8]);
            $person->setTelephone($data[9]);
            $person->setMobile($data[10]);
            $person->setDisability(false);
            $person->setNotes($data[16]);

            $person->setGender($data[48]);

            $person->setIsActive(true);

            $em->persist($person);
        }

        $em->flush();

        fclose($fh);
    }
}
