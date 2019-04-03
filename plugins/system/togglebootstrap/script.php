<?php
/**
 * @copyright   (C) 2011 - 2017 Mike Feng Jinglong - All rights reserved.
 * @license  GNU General Public License, version 3 (http://www.gnu.org/licenses/gpl-3.0.html)
 * @author  Mike Feng Jinglong <mike@simbunch.com>
 * @url   http://www.simbunch.com/license/
*/

// No direct access
defined('_JEXEC') or die('Restricted access');

class plgSystemToggleBootstrapInstallerScript {
	function install($parent) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->update($db->quoteName('#__extensions'))
			->set($db->quoteName('enabled') . ' = ' . $db->quote(1))
			->where($db->quoteName('element') . ' = ' . $db->quote('togglebootstrap'))
			->where($db->quoteName('folder') . ' = ' . $db->quote('system'));
		$db->setQuery($query);
		
		try {
			$db->execute();
		}
		catch (RuntimeException $e) {
			JError::raiseWarning('WARNING', 'ToggleBootstrap System plugin <strong>not enabled</strong>. Please enable it manually by going to <a href="'.JURI::root().'administrator/index.php?option=com_plugins">Joomla\'s Plugins manager</a>.');
			return;
		}
		$app = JFactory::getApplication();
		$app->enqueueMessage('ToggleBootstrap System plugin <strong>enabled</strong>.');
	}

	function update($parent) {
		if (self::isEnabled()) {
			$app = JFactory::getApplication();
			$app->enqueueMessage('ToggleBootstrap System plugin <strong>enabled</strong>.');
		}
		else
			JError::raiseWarning('WARNING', 'ToggleBootstrap System plugin <strong>not enabled</strong>. Please enable it manually by going to <a href="'.JURI::root().'administrator/index.php?option=com_plugins">Joomla\'s Plugins manager</a>.');
	}

	function isEnabled() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->quoteName('enabled'))
			->from($db->quoteName('#__extensions'))
			->where($db->quoteName('element') . ' = ' . $db->quote('togglebootstrap'))
			->where($db->quoteName('folder') . ' = ' . $db->quote('system'));
		$db->setQuery($query);
		return $db->loadResult();
	}
}
?>