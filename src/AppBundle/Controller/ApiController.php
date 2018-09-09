<?php

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api")
 */
class ApiController extends Controller
{
    /**
     * @Route("/activity", name="api_activity_list")
     */
    public function activityListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        //Lookup all activities happening in the next 6 moths
        $start = new \DateTime();
        $end = new \DateTime();
        $end->add(new \DateInterval('P6M'));
        $activities = $em->getRepository('AppBundle:ManagedActivity')->findActivitiesBetweenDates($start, $end, null);

        // Array to hold our response
        $response = [];

        // Loop over each activity and add to the response array
        foreach ($activities as $activity) {
            $response[] = [
                'id' => $activity->getId(),
                'name' => $activity->getName(),
                'activity' => $activity->getDescription(),
                'activityStart' => $activity->getActivityStart()->format('Y-m-d H:i'),
                'activityEnd' => $activity->getActivityEnd()->format('Y-m-d H:i'),
                'type' => $activity->getActivityType()->getType(),
                'signupStart' => $activity->getSignupStart() ? $activity->getSignupStart()->format('Y-m-d H:i') : null,
                'signupEnd' => $activity->getSignupEnd()? $activity->getSignupEnd()->format('Y-m-d H:i') : null
            ];
        }

        // Return the list of activities as a JSON string
        return new JsonResponse($response);
    }
}
