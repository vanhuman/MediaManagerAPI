<?php

namespace Templates;

use Models\Label;

class LabelTemplate implements TemplateInterface
{
    /**
     * @var Label $label
     */
    protected $label;

    /**
     * LabelTemplate constructor.
     * @param Label $label
     */
    public function __construct($label)
    {
        $this->label = $label;
    }

    /**
     * @param bool $includeWrapper
     * @return array|null
     */
    public function getArray($includeWrapper = true)
    {
        if (isset($this->label)) {
            $label = [
                'id' => $this->label->getId(),
                'name' => $this->label->getName(),
            ];
        }
        if ($includeWrapper) {
            $label = [
                'label' => $label
            ];
        }
        return $label;
    }
}