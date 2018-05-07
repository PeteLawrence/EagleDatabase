<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class ForgetOldMembersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
        ->setName('eagledb:forgetoldmembers')
        ->addArgument('date', InputArgument::REQUIRED, 'Expire members who have not enrolled since this date');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $personService = $this->getContainer()->get('AppBundle\\Services\\PersonService');

        $personRepository = $em->getRepository('AppBundle:Person');

        $date = new \DateTime($input->getArgument('date'));

        $expiredPersons = $personRepository->findMembersNotEnrolledSince($date);
        $expiredPersonsRows = [];
        foreach ($expiredPersons as $expiredPerson) {
            $date = ($expiredPerson->getMostRecentRegistration() ? $expiredPerson->getMostRecentRegistration()->getRegistrationDateTime()->format('jS F Y') : 'Never');
            $expiredPersonsRows[] = [ $expiredPerson->getId(), $expiredPerson->getName(), $date];
        }

        //Display table
        $table = new Table($output);
        $table
            ->setHeaders(array('ID', 'Name'))
            ->setRows($expiredPersonsRows)
            ->setStyle('compact');
        ;
        $table->render();

        //Ask user to confirm
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Are you sure that you want to forget all of these people?', false);

        if ($helper->ask($input, $output, $question)) {
            foreach ($expiredPersons as $expiredPerson) {
                $personService->forgetPerson($expiredPerson);
            }
        }
    }
}
