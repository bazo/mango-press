<?php

namespace MangoPress\AdminModule;

use \BaseForm as Form;

/**
 * Homepage presenter.
 */
class SettingsPresenter extends SecuredPresenter
{

	/** @var \Settings\SettingsManager */
	private $settingsManager;


	public function __construct(\Settings\SettingsManager $settingsManager)
	{
		$this->settingsManager = $settingsManager;
	}


	public function renderDefault()
	{
		$settings = $this->settingsManager->getSettings();
		$this['formSettings']->setDefaults($settings->toArray());
	}


	protected function createComponentFormSettings()
	{
		$form = new Form;

		$form->addText('blogName', 'Blog name')->setRequired();
		$form->addText('blogDescription', 'Blog description')->setRequired();
		
		$form->addSubmit('btnSubmit');
		$form->onSuccess[] = callback($this, 'formSettingsSubmitted');

		return $form;
	}


	public function formSettingsSubmitted(Form $form)
	{
		$values = $form->getValues();
		
		try {
			$this->settingsManager->updateSettings($values);
			$this->flash('Settings updated.');
			$this->redirect('this');
		} catch(\MongoCursorException $e) {
			$this->flash($e->getMessage(), 'error');
		}
	}


}


