<?php

namespace app\components;

use yii\base\Component;

class TestService extends Component
{
    private $prop = 'default value';

    /**
     * Получить свойство $prop.
     * @return string
     */
    public function getProp()
    {
        return $this->prop;
    }

    /**
     * Установить свойство $prop.
     * @param $value
     */
    public function setProp( $value )
    {
        $this->prop = $value;
    }
}