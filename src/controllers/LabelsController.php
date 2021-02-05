<?php

namespace Controllers;

use Psr\Container\ContainerInterface;

use Handlers\LabelsHandler;
use Models\Label;
use Templates\LabelsTemplate;
use Templates\LabelTemplate;

class LabelsController extends RestController
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->handler = new LabelsHandler($this->container->get('db'));
    }

    /**
     * @param Label | Label[] $labels
     * @return LabelsTemplate | LabelTemplate
     */
    protected function newTemplate($labels)
    {
        if (is_array($labels)) {
            return new LabelsTemplate($labels);
        } else {
            return new LabelTemplate($labels);
        }
    }
}
