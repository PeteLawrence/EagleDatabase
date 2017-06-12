<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;

class LookupWeatherCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
        ->setName('eagledb:lookup:weather');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $em = $this->getContainer()->get('doctrine')->getManager();
        $logger = $this->getContainer()->get('logger');

        //Set the timezone
        date_default_timezone_set('Europe/London');

        //Display title
        $io->title('Weather Lookup');

        //Find Activities taking place
        $activities  = $em->getRepository('AppBundle:Activity')->findAll();

        //
        $now = new \DateTime();

        $io->text('Run at: ' . $now->format('Y-m-d H:i:s'));

        foreach ($activities as $activity) {
            if ($activity->getActivityStart() <= $now && $activity->getActivityEnd() >= $now) {
                $io->text('Looking up weather for activity ' . $activity->getId());
                $forecast = $this->getForecast(
                    $activity->getStartLocation()->getLongitude(),
                    $activity->getStartLocation()->getLatitude()
                );

                $weather = new \AppBundle\Entity\WeatherDataPoint;
                $weather->setTime(new \DateTime('@' . $forecast->time));
                $weather->setTimeZone($forecast->timezone);
                $weather->setSummary($forecast->summary);
                $weather->setIcon($forecast->icon);
                $weather->setPrecipitationIntensity($forecast->precipIntensity);
                $weather->setPrecipitationProbability($forecast->precipProbability);
                $weather->setPrecipitationType($forecast->precipType);
                $weather->setTemperature($forecast->temperature);
                $weather->setWindSpeed($forecast->windSpeed);
                $weather->setWindBearing($forecast->windBearing);
                $weather->setVisibility($forecast->visibility);
                $weather->setCloudCover($forecast->cloudCover);
                $weather->setHumidity($forecast->humidity);
                $weather->setPressure($forecast->pressure);

                //Attach the forecast to the activity
                $weather->setActivity($activity);

                $em->persist($weather);
            }
        }

        $io->text('Finished');

        $em->flush();
    }

    private function getForecast($longitude, $latitude)
    {
        $arrContextOptions=array(
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        );

        $forecast = json_decode(file_get_contents(sprintf(
            'https://api.darksky.net/forecast/%s/%s,%s?units=si',
            $this->getContainer()->getParameter('darksky.key'),
            $latitude,
            $longitude
        ), false, stream_context_create($arrContextOptions)));

        //Add in the timezone
        $forecast->currently->timezone = $forecast->timezone;

        return $forecast->currently;
    }
}
