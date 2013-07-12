<?php

namespace MangoPress\AdminModule;

use Posts\PostsManager;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;
use Michelf\Markdown;
use \BaseForm as Form;
/**
 * Posts presenter.
 */
class PostsPresenter extends SecuredPresenter
{

	/** @var PostsManager */
	private $postsManager;

	/** @var \Post */
	private $post;


	public function __construct(PostsManager $postsManager)
	{
		$this->postsManager = $postsManager;
	}


	public function actionEdit($id = null)
	{
		if (!is_null($id)) {
			$this->post = $this->postsManager->getPostById($id);
		}
	}


	public function renderEdit()
	{
		if (!is_null($this->post)) {
			$this['formEditPost']->setDefaults([
				'post_id' => $this->post->getId(),
				'title' => $this->post->getTitle(),
				'text' => $this->post->getText(),
				'publish' => $this->post->isPublished(),
				'tags' => implode(',', $this->post->getTags())
			]);
		}
	}


	public function renderDetail($id)
	{
		$this->template->post = $this->postsManager->getPostById($id);
	}


	public function renderDefault()
	{
		$this->template->posts = $this->postsManager->getAllPosts();
	}


	protected function createComponentFormEditPost()
	{
		$form = new Form;

		$form->addHidden('post_id');
		$form->addText('title', 'Title')->setRequired();
		$form->addTextArea('text', 'Text')->setRequired();
		$form['text']->getControlPrototype()->{'data-provide'} = 'markdown';
		$form->addText('tags', 'Tags');
		
		$form->addCheckbox('publish', 'Publish?')->setDefaultValue(TRUE);

		$form->addSubmit('btnSave', 'Save');
		$form->onSuccess[] = callback($this, 'formEditPostSubmitted');

		return $form;
	}


	public function formEditPostSubmitted(Form $form)
	{
		$values = $form->getValues();

		$tagsString = $form->getHttpData('tags-values');
		$tags = explode(',', $tagsString);
		
		$html = Markdown::defaultTransform($values->text);

		if (is_null($this->post)) {
			$this->createPost($values->title, $values->text, $html, $tags, $values->publish);
		} else {
			$this->updatePost($this->post, $values->title, $values->text, $html, $tags, $values->publish);
		}
	}


	private function createPost($title, $text, $html, $tags, $publish)
	{
		try {
			$this->postsManager->createPost($title, $text, $html, $tags, $publish);
			$this->flash('Post successfully created');
			$this->redirect('default');
		} catch (\MongoCursorException $e) {
			$this->flash($e->getMessage(), 'error');
		}
	}


	private function updatePost($post, $title, $text, $html, $tags, $publish)
	{
		try {
			$this->postsManager->updatePost($post, $title, $text, $html, $tags, $publish);
			$this->flash('Post successfully updated');
			$this->redirect('default');
		} catch (\MongoCursorException $e) {
			$this->flash($e->getMessage(), 'error');
		}
	}


	public function handlePublish($id)
	{
		$this->postsManager->publishPost($id);
		$this->redirect('this');
	}


	public function handleUnpublish($id)
	{
		$this->postsManager->unpublishPost($id);
		$this->redirect('this');
	}


}


