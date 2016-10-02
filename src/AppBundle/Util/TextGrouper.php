<?php

namespace AppBundle\Util;

class TextGrouper
{
    private $groups;

    public function __construct()
    {
        $this->groups = [];
    }

    public function addItem($groupingValue, $add = 1)
    {
        $found = false;
        for ($i = 0; $i < sizeof($this->groups); $i++) {
            if ($this->groups[$i]['name'] == $groupingValue) {
                $this->groups[$i]['count'] += $add;
                $found = true;
            }
        }

        if (!$found) {
            $this->groups[] = [ 'name' => $groupingValue, 'count' => $add];
        }
    }

    public function getGroups()
    {
        return $this->groups;
    }
}
