<?php

namespace dlds\assets\traits;

use dlds\assets\events\AssetFilterEvent;

trait AssetFilterViewTrait
{

    /**
     * @inheritdoc
     */
    public function registerAssetBundle($name, $position = null)
    {
        $event = new AssetFilterEvent([
            'bundle' => $name,
            'position' => $position,
        ]);

        $this->trigger(AssetFilterEvent::EVT_BEFORE_BUNDLE_REGISTRATION, $event);

        if (!$event->isPrevented()) {
            return false;
        }

        return parent::registerAssetBundle($name, $position);
    }

}
