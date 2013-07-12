<?php

namespace MangoPress\AdminModule;

use Nette,
	Model;


/**
 * Homepage presenter.
 */
class SecuredPresenter extends BasePresenter
{

	protected function startup()
	{
		parent::startup();
		
		if(!$this->user->isLoggedIn()) {
			$this->redirect('sign:in');
		}
	}
	
	public function actionLogout()
	{
		$this->user->logout($clearIdentity = TRUE);
		$this->flashMessage('You have been signed out.');
		$this->redirect('sign:in');
	}

}
