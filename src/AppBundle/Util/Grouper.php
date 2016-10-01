<?php

namespace AppBundle\Util;

class Grouper
{
    private $groupingLimits;


    public function __construct($groupingLimits)
    {
        $this->groupingLimits = $groupingLimits;

        $this->groups = [];
        $prev = 0;
        for ($i = 0; $i < sizeof($this->groupingLimits); $i++) {
            $this->groups[$i] = [ 'count' => 0, 'name' => sprintf('%s-%s', $prev, $this->groupingLimits[$i])];
            $prev = $this->groupingLimits[$i];
        }
    }

    public function addItem($groupingValue)
    {
        for ($i = 0; $i < sizeof($this->groupingLimits); $i++) {
            if ($this->groupingLimits[$i] > $groupingValue) {
                $this->groups[$i]['count']++;
                return;
            }
        }
    }

    public function getGroups()
    {
        return $this->groups;
    }
}
