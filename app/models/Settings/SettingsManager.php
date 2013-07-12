<?php

namespace Settings;

/**
 * UserManager
 *
 * @author Martin Bažík <martin@bazo.sk>
 */
class SettingsManager extends \BaseManager
{

	/**
	 * @return \Settings
	 */
	public function getSettings()
	{
		return $this->dm->getRepository('Settings')->createQueryBuilder()->getQuery()->getSingleResult();
	}
	

	public function updateSettings($values)
	{
		$settings = $this->getSettings();
		
		foreach($values as $setting => $value) {
			$settings->$setting = $value;
		}
		
		$this->dm->persist($settings);
		$this->dm->flush();
	}

	public function setBlogName($blogName)
	{
		$settings = $this->getSettings();
		
		if($settings === NULL) {
			$settings = new \Settings;
		}
		
		$settings->setBlogName($blogName);
		
		$this->dm->persist($settings);
		$this->dm->flush();
	}

}


