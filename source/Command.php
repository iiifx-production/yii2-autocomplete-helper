<?php

namespace iiifx\yii2\autocomplete;

use yii\helpers\FileHelper;

class Command
{
    /**
     *
     */
    public static function execute ()
    {
        ( new self() )->checkSimple()->checkAdvanced();
    }


    /**
     * @return $this
     */
    protected function checkSimple ()
    {
        return $this;
    }

    protected function checkAdvanced ()
    {
        echo $this->getApplicationPath();
    }

    /**
     * @return string
     */
    public function getApplicationPath ()
    {
        return FileHelper::normalizePath( dirname( dirname( dirname( dirname( __DIR__ ) ) ) ) );
    }
}

/*
    "scripts": {
        "yii2-autocomplete": "iiifx\\yii2\\autocomplete\\Command::execute"
    }
*/
