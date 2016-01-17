<?php

namespace iiifx\Yii2\Autocomplete;

class Builder extends Base\AbstractBuilder
{
    /**
     * @var Base\AbstractReader
     */
    protected $reader;

    /**
     * @param Base\AbstractReader $reader
     */
    public function __construct ( Base\AbstractReader $reader )
    {
        $this->reader = $reader;
    }

    /**
     * @return Base\AbstractReader
     */
    public function getReader ()
    {
        return $this->reader;
    }
}
