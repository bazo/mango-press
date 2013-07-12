<?php

use Nette\Application\UI\Form;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;

/**
 * Description of BaseForm
 *
 * @author Martin Bažík <martin@bazo.sk>
 */
class BaseForm extends Form
{
	public function __construct(\Nette\ComponentModel\IContainer $parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);
		
		$this->setRenderer(new BootstrapRenderer);
	}
}

