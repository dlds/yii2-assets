<?php

namespace dlds\assets\events;

class AssetFilterEvent extends \yii\base\Event
{

    /**
     * Events names
     */
    const EVT_BEFORE_BUNDLE_REGISTRATION = 'evt_before_bundle_registration';

    /**
     * @var string
     */
    public $bundle;

    /**
     * @var string
     */
    public $position;

    /**
     * @var boolean
     */
    protected $prevented = false;

    /**
     * Prevents assets registration
     */
    public function prevent()
    {
        $this->prevented = true;
    }

    /**
     * Indicates if assets registration is prevented
     * @return boolean
     */
    public function isPrevented()
    {
        return $this->prevented;
    }

}
