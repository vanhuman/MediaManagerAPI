<?php

namespace Templates;

use Models\Format;

class FormatsTemplate implements TemplateInterface
{
    /**
     * @var $format Format[]
     */
    protected $formats;

    /**
     * AlbumsTemplate constructor.
     * @param $formats Format[]
     */
    public function __construct($formats)
    {
        $this->formats = $formats;
    }

    /**
     * @return array
     */
    public function getArray() {
        foreach ($this->formats as $format) {
            $formatTemplate = new FormatTemplate($format);
            $formatsArray[] = $formatTemplate->getArray();
        }
        return isset($formatsArray) ? $formatsArray : [];
    }

}