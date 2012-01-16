<?php
class Frontend_Helper_DigestKey extends Zend_Controller_Action_Helper_Abstract
{
    public function init() {}
	
	public function isValid($digestKey, $list)
	{
		if ($digestKey == null)
			return false;
		
		$digestKey = (string) $digestKey;
		
		if (Vfr_DigestKey::generate($list) == $digestKey)
			return true;
		
		return false;
	}
	
	private function injectDigestKey($list)
	{
		$this->getActionController()->view->digestKey = Vfr_DigestKey::generate($list);
	}
	
	public function direct($list)
    {
		$this->injectDigestKey($list);
	}
}