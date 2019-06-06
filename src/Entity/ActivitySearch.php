<?php

namespace App\Entity;

class ActivitySearch
{
    private $creatorName;

    private $activityName;

    private $activityType;

    /**
     * @return mixed
     */
    public function getCreatorName()
    {
        return $this->creatorName;
    }

    /**
     * @param mixed $creatorName
     * @return ActivitySearch
     */
    public function setCreatorName($creatorName)
    {
        $this->creatorName = $creatorName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActivityName()
    {
        return $this->activityName;
    }

    /**
     * @param mixed $activityName
     */
    public function setActivityName($activityName): void
    {
        $this->activityName = $activityName;
    }

    /**
     * @return mixed
     */
    public function getActivityType()
    {
        return $this->activityType;
    }

    /**
     * @param mixed $activityType
     */
    public function setActivityType($activityType): void
    {
        $this->activityType = $activityType;
    }

}
