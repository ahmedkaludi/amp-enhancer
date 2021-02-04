<?php


namespace templates\smartslider3;


use templates\smartslider3\EnhancerAbstractController;

class EnhancerControllerSlider extends EnhancerAbstractController {

    public function actionDisplay($sliderID, $usage) {

        $view = new ViewDisplay($this);

        $view->setSliderIDorAlias($sliderID);
        $view->setUsage($usage);

        $view->display();
    }
}