<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadEagleCanoeClubData extends \AppBundle\DataFixtures\EagleFixture implements FixtureInterface
{
    private $manager;

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $this->loadActivityTypes();
        $this->loadMembershipPeriods();
        $this->loadMembershipTypes();
        $this->loadMembershipTypePeriods();
        $this->loadParticipantRoles();
        $this->loadParticipantStatuses();
        $this->loadQualifications();

        $this->manager->flush();
    }


    private function loadActivityTypes()
    {
        $activityTypes = [
            'activitytype-clubnight' => [
                'type' => 'Club Night'
            ],
            'activitytype-whitewaterweekend' => [
                'type' => 'White Water Weekend'
            ],
            'activitytype-touringweekend' => [
                'type' => 'Touring Weekend'
            ],
            'activitytype-poolsession' => [
                'type' => 'Pool Session'
            ],
            'activitytype-localpaddle' => [
                'type' => 'Local Paddle'
            ]
        ];

        $this->loadFromArray('\AppBundle\Entity\ActivityType', $activityTypes, $this->manager);
    }


    private function loadMembershipPeriods()
    {
        $fixtures = [
            'membershipPeriod-2015' => [
                'fromDate' => new \DateTime('2015-04-01'),
                'toDate' => new \DateTime('2016-03-31')
            ],
            'membershipPeriod-2016' => [
                'fromDate' => new \DateTime('2016-04-01'),
                'toDate' => new \DateTime('2017-03-31')
            ],
            'membershipPeriod-2017' => [
                'fromDate' => new \DateTime('2017-04-01'),
                'toDate' => new \DateTime('2018-03-31')
            ]
        ];

        $this->loadFromArray('\AppBundle\Entity\MembershipPeriod', $fixtures, $this->manager);
    }


    private function loadMembershipTypes()
    {
        $fixtures = [
            'membershipType-adult' => [
                'type' => 'Adult'
            ],
            'membershipType-youth' => [
                'type' => 'Youth'
            ],
            'membershipType-coach' => [
                'type' => 'Coach'
            ]
        ];

        $this->loadFromArray('\AppBundle\Entity\MembershipType', $fixtures, $this->manager);
    }


    private function loadMembershipTypePeriods()
    {
        $fixtures = [
            'membershipTypePeriod-adult2015' => [
                'membershipType' => $this->getReference('membershipType-adult'),
                'membershipPeriod' => $this->getReference('membershipPeriod-2015'),
                'price' => 75.00
            ],
            'membershipTypePeriod-youth2015' => [
                'membershipType' => $this->getReference('membershipType-youth'),
                'membershipPeriod' => $this->getReference('membershipPeriod-2015'),
                'price' => 25.00
            ],
            'membershipTypePeriod-coach2015' => [
                'membershipType' => $this->getReference('membershipType-coach'),
                'membershipPeriod' => $this->getReference('membershipPeriod-2016'),
                'price' => 15.00
            ],
            'membershipTypePeriod-adult2016' => [
                'membershipType' => $this->getReference('membershipType-adult'),
                'membershipPeriod' => $this->getReference('membershipPeriod-2016'),
                'price' => 75.00
            ],
            'membershipTypePeriod-youth2016' => [
                'membershipType' => $this->getReference('membershipType-youth'),
                'membershipPeriod' => $this->getReference('membershipPeriod-2016'),
                'price' => 25.00
            ],
            'membershipTypePeriod-coach2016' => [
                'membershipType' => $this->getReference('membershipType-coach'),
                'membershipPeriod' => $this->getReference('membershipPeriod-2016'),
                'price' => 15.00
            ],
        ];

        $this->loadFromArray('\AppBundle\Entity\MembershipTypePeriod', $fixtures, $this->manager);
    }


    private function loadParticipantRoles()
    {
        $fixtures = [
            'participantRole-organiser' => [
                'role' => 'Organiser'
            ],
            'participantRole-coach' => [
                'role' => 'Coach'
            ],
            'participantRole-participant' => [
                'role' => 'Participant'
            ],
        ];

        $this->loadFromArray('\AppBundle\Entity\ParticipantRole', $fixtures, $this->manager);
    }


    private function loadParticipantStatuses()
    {
        $fixtures = [
            'participantStatus-attending' => [
                'status' => 'Attending',
                'countsTowardsSize' => true
            ],
            'participantRole-cancelled' => [
                'status' => 'Cancelled',
                'countsTowardsSize' => false
            ],
            'participantRole-shortlist' => [
                'status' => 'Shortlist',
                'countsTowardsSize' => false
            ],
        ];

        $this->loadFromArray('\AppBundle\Entity\ParticipantStatus', $fixtures, $this->manager);
    }


    private function loadQualifications()
    {
        $fixtures = [
            'qualification-bculevel1' => [
                'name' => 'BCU Level 1'
            ],
            'qualification-bculevel2' => [
                'name' => 'BCU Level 2'
            ],
            'qualification-bculevel3' => [
                'name' => 'BCU Level 3'
            ]
        ];

        $this->loadFromArray('\AppBundle\Entity\Qualification', $fixtures, $this->manager);
    }
}
