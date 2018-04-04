<?php

namespace app\components;

use yii\base\Component;

class TestService extends Component
{
    private $prop = 'default value';

    public function getProp()
    {
        return $this->prop;
    }
}