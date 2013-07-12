<?php

namespace MangoPress\WebModule;

use \BaseForm as Form;
use Settings\SettingsManager;
use Posts\TagsManager;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends \MangoPress\BasePresenter
{
	/** @var \Settings */
	private $settings;
	
	/** @var SettingsManager */
	private $settingsManager;
	
	/** @var TagsManager */
	private $tagsManager;
	
	public function inject(SettingsManager $settingsManager, TagsManager $tagsManager)
	{
		$this->settingsManager = $settingsManager;
		$this->tagsManager = $tagsManager;
	}

	protected function startup()
	{
		parent::startup();
		$this->settings = $this->settingsManager->getSettings();
	}
	
	protected function beforeRender()
	{
		parent::beforeRender();
		$this->template->settings = $this->settings;
		$this->template->registerHelper('timeAgo', 'Helpers::timeAgoInWords');
		$tagsCriteria = new \Posts\TagsCriteria;
		$tagsCriteria->hydrate = FALSE;
		$this->template->tags = $this->tagsManager->getAllTags($tagsCriteria);
	}
	
	protected function createComponentFormSearch()
	{
		$form = new Form;
		
		$form->addText('search', 'Search');
		$form->addSubmit('btnSubmit', 'Search');
		
		$form->onSuccess[] = callback($this, 'formSearchSubmitted');
		
		return $form;
	}
	
	public function formSearchSubmitted(Form $form)
	{
		$values = $form->getValues();
		
		$searchTerm = $values->search;
		
		$this->redirect('homepage:search', ['search' => $searchTerm]);
	}
}
