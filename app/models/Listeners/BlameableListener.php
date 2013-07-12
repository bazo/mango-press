<?php

namespace MangoPress\Listeners;

use Gedmo\Blameable\BlameableListener as GedmoBlameableListener;

/**
 * Description of BlameableListener
 *
 * @author Martin Bažík <martin@bazo.sk>
 */
class BlameableListener extends GedmoBlameableListener
{
	/** @var \SystemContainer */
	private $container;
	
	public function __construct(\SystemContainer $container)
	{
		parent::__construct();
		$this->container = $container;
	}
	
	public function getUserValue($meta, $field)
	{
		$dm = $this->container->getByType('\Doctrine\ODM\MongoDB\DocumentManager');
		$identity = $this->container->getService('user')->getIdentity();
		$user = $dm->getRepository('user')->find($identity->getId());
		return $user;
	}
}

