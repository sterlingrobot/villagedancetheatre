<?php
/**
 * @copyright   (C) 2011 - 2017 Mike Feng Jinglong - All rights reserved.
 * @license  GNU General Public License, version 3 (http://www.gnu.org/licenses/gpl-3.0.html)
 * @author  Mike Feng Jinglong <mike@simbunch.com>
 * @url   http://www.simbunch.com/license/
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );

class plgSystemToggleBootstrap extends JPlugin {
	var $bsfiles		= NULL;
	var $document		= NULL;
	var $input			= NULL;

	function __construct(& $subject, $config) {
		parent::__construct($subject, $config);
		$this->bsfiles = array('js'=>NULL, 'css'=>NULL);
	}
		
	function onBeforeCompileHead() {
		if (!$this->params->get('toggle_admin', '0') && JFactory::getApplication()->isAdmin()) return true;
		$this->document = JFactory::getDocument();
		$this->input = JFactory::getApplication()->input;
		if ($this->document->getType() != 'html') return;

		$this->setFiles();
		$this->toggleBootstrap();
		$this->disableMooTools();
	}
	
	function toggleBootstrap() {
		$document = $this->document;
		$option = $this->input->get('option');
		$toggle_bs_on = array_map('trim', preg_split("@\n@", $this->params->get('toggle_bs_on', ''), NULL, PREG_SPLIT_NO_EMPTY));
		$toggle_bs_except = array_map('trim', preg_split("@\n@", $this->params->get('toggle_bs_except', ''), NULL, PREG_SPLIT_NO_EMPTY));
		if ( (count($toggle_bs_on) && !in_array($option, $toggle_bs_on)) || (count($toggle_bs_except) && in_array($option, $toggle_bs_except)) )
			return;
		if ($this->bsfiles['js']) {
			$this->insertAsset($document->_scripts, JURI::root(true).'/plugins/system/t3/base-bs3/bootstrap/js/bootstrap.js', $this->bsfiles['js']);
			$this->insertAsset($document->_scripts, JURI::root(true).'/media/jui/js/bootstrap.min.js', $this->bsfiles['js']);
		}
		if ($this->bsfiles['css']) {
			$inserted = $this->insertAsset($document->_styleSheets, JURI::root(true).'/media/jui/css/bootstrap.min.css', $this->bsfiles['css'], (int)$this->params->get('joomla_css', '0'));
			if (!$inserted && $this->params->get('force_css', '0'))
				$document->addStyleSheet($this->bsfiles['css']);
		}
	}
	
	function disableMooTools(){
		$document = $this->document;
		$option = $this->input->get('option');
		$disable_mootools_on = array_map('trim', preg_split("@\n@", $this->params->get('disable_mootools_on', ''), NULL, PREG_SPLIT_NO_EMPTY));
		$disable_mootools_except = array_map('trim', preg_split("@\n@", $this->params->get('disable_mootools_except', ''), NULL, PREG_SPLIT_NO_EMPTY));
		if ( (count($disable_mootools_on) && !in_array($option, $disable_mootools_on)) || (count($disable_mootools_except) && in_array($option, $disable_mootools_except)) )
			return;
		$disable_core = (int)$this->params->get('disable_mootools_core', 1);
		$disable_more = (int)$this->params->get('disable_mootools_more', 1);
		if ($disable_core) {
			unset($document->_scripts[JURI::root(true).'/media/system/js/mootools-core.js']);
			unset($document->_scripts[JURI::root(true).'/media/system/js/mootools-more.js']);
		}
		elseif ($disable_more) {
			unset($document->_scripts[JURI::root(true).'/media/system/js/mootools-more.js']);
		}
	}
	
	function insertAsset(&$array, $old_key, $new_key, $insert=false) {
		$keys = array_keys($array);
		$index = array_search($old_key, $keys);
		if ($index === false) return false;
		
		if ($insert) {
			$params = array('mime'=>'text/css', 'media'=>NULL, 'attribs'=>array());
			$array = array_slice($array, 0, $index+1, true) + array($new_key=>$params) + array_slice($array, $index+1, count($array)-$index, true);
		}
		else {
			$keys[$index] = $new_key;
			$array = array_combine($keys, array_values($array));
		}
		return $index;
	}
	
	function setFiles() {
		$source = $this->params->get('source', 'cdn');
		$js_version = (int)$this->params->get('js_version', '3');
		$css_version = (int)$this->params->get('css_version', '3');
		if ($source == 'cdn') {
			if ($js_version == 3)
				$this->bsfiles['js'] = '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js';
			elseif ($js_version == 4)
				$this->bsfiles['js'] = '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js';
			if ($css_version == 3)
				$this->bsfiles['css'] = '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css';
			elseif ($css_version == 4)
				$this->bsfiles['css'] = '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css';
		}
		elseif ($source == 'local') {
			if ($js_version != 2)
				$this->bsfiles['js'] = JURI::base(true).'/plugins/system/togglebootstrap/assets/bootstrap/ver'.$js_version.'/bootstrap.min.js';
			if ($css_version != 2)
				$this->bsfiles['css'] = JURI::base(true).'/plugins/system/togglebootstrap/assets/bootstrap/ver'.$css_version.'/bootstrap.min.css';
		}
	}
}
?>