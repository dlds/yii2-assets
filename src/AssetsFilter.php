<?php

namespace dlds\assets;

use yii\helpers\ArrayHelper;
use dlds\assets\events\AssetFilterEvent;

/**
 * This is filter class ensures assets filtering
 * ---
 * From current request all specified assets bundles will be removed to 
 * avoid duplication in DOM.
 * ---
 * @see http://www.yiiframework.com/doc-2.0/guide-structure-assets.html
 */
class AssetsFilter extends \yii\base\ActionFilter
{

    /**
     * @var array actions which will be checked
     */
    public $actions = [];

    /**
     * @var array bundles to be ignored
     */
    public $bundles = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    /**
     * @var array bundles to be added to ignore list
     */
    public $addBundles = [];

    /**
     * @var boolean targets only ajax requests
     */
    public $ajaxOnly = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $traits = class_uses(\Yii::$app->view);

        if (!ArrayHelper::isIn(traits\AssetFilterViewTrait::class, $traits)) {
            throw new \yii\base\Exception(sprintf('AssetFilter says: "%s" must uses "%s" trait', get_class(\Yii::$app->view), traits\AssetFilterViewTrait::class));
        }

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($this->_isAllowed($action)) {
            $this->_handle();
        }

        return parent::beforeAction($action);
    }

    /**
     * Handles assets filtering
     */
    private function _handle()
    {
        $bundles = \yii\helpers\ArrayHelper::merge($this->bundles, $this->addBundles);

        \Yii::$app->view->on(AssetFilterEvent::EVT_BEFORE_BUNDLE_REGISTRATION, function($e) use($bundles) {

            if (ArrayHelper::isIn($e->bundle, $bundles)) {
                $e->prevent();
            }
        });
    }

    /**
     * Indicates if filtering is allowed
     * @return boolean
     */
    private function _isAllowed(\yii\base\Action $action)
    {
        if ($this->ajaxOnly && !\Yii::$app->request->isAjax) {
            return false;
        }

        if ($this->actions && !in_array($action->id, $this->actions)) {
            return false;
        }

        return true;
    }

}
