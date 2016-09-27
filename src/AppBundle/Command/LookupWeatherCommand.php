<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class LookupWeatherCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
        ->setName('eagledb:lookup:weather');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();


        //Find Activities taking place
        $activity = $em->getRepository('AppBundle:Activity')->findOneById(1);

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
        $weather->setTemperature($forecast->temperature);
        $weather->setWindSpeed($forecast->windSpeed);
        $weather->setWindBearing($forecast->windBearing);
        $weather->setVisibility($forecast->visibility);
        $weather->setCloudCover($forecast->cloudCover);

        //Attach the forecast to the activity
        $weather->setActivity($activity);

        $em->persist($weather);
        $em->flush();
    }

    private function getForecast($longitude, $latitude)
    {
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
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
