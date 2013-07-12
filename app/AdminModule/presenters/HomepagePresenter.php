<?php

namespace MangoPress\AdminModule;

use Nette,
	Model;


/**
 * Homepage presenter.
 */
class HomepagePresenter extends SecuredPresenter
{

	public function renderDefault()
	{
		$this->template->anyVariable = 'any value';
	}

}
