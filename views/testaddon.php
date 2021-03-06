<?php
/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('ELEMENTOR_WIDGET_PRO_INC') or die('Restricted access');


class UniteCreatorTestAddonView{
	
	protected $showToolbar = true;
	protected $showHeader = true;
	protected $addon;
	protected $addonID;
	protected $isPreviewMode;	
	protected $isTestData1;
	protected $textSingle, $textPlural;
	
	
	/**
	 * constructor
	 */
	public function __construct(){
		
		$this->putHtml();
	}
	
	
	/**
	 * get header text
	 */
	protected function getHeader(){
		
		$addonTitle = $this->addon->getTitle();
		
		$headerTitle = esc_html__("Test ","unlimited_elementor_elements").$this->textSingle;
		$headerTitle .= " - ".$addonTitle;
		
		return($headerTitle);
	}
	
	
	/**
	 * put header html
	 */
	protected function putHeaderHtml(){
		
		$headerTitle = $this->getHeader();
		require EWPHelper::getPathTemplate("header");
		
	}
	
	/**
	 * init by addon type
	 */
	private function initByAddonType($objType){
		
		$this->textSingle = $objType->textSingle;
		$this->textPlural = $objType->textPlural;
		
	}
	
	
	/**
	 * init by addon
	 */
	private function initByAddon($addonID){
		
		if(empty($addonID))
			UniteFunctionsUC::throwError("Addon ID not given");
		
		$this->addonID = $addonID;
		
		$addon = new UniteCreatorAddon();
		$addon->initByID($addonID);
		
		$this->addon = $addon;
		
		$objType = $addon->getObjAddonType();
		
		$this->initByAddonType($objType);
		
	}
	
	
	/**
	 * put html
	 */
	private function putHtml(){
		
		HelperHtmlUC::putAddonTypesBrowserDialogs();
		
		$addonID = UniteFunctionsUC::getGetVar("id","",UniteFunctionsUC::SANITIZE_ID);
		
		$this->initByAddon($addonID);
		
		$objAddons = new UniteCreatorAddons();
		
		$isNeedHelperEditor = $objAddons->isHelperEditorNeeded($this->addon);
		
		
		$addonTitle = $this->addon->getTitle();
		
		$addonType = $this->addon->getType();
		$objAddonType = $this->addon->getObjAddonType();
		
		
		$urlEditAddon = EWPHelper::getViewUrl_EditAddon($addonID);
		
		$urlTestWithData = EWPHelper::getViewUrl_TestAddon($addonID, "loaddata=test");
		
		//init addon config
		$addonConfig = new UniteCreatorAddonConfig();
		$addonConfig->setStartAddon($this->addon);
		
		$this->isTestData1 = $this->addon->isTestDataExists(1);
		
		//get addon data
		$addonData = null;
		$isLoadData = UniteFunctionsUC::getGetVar("loaddata","",UniteFunctionsUC::SANITIZE_NOTHING);
		
		if($isLoadData == "test" && $this->isTestData1 == true)
			$addon->setValuesFromTestData(1);
		
		$isPreviewMode = UniteFunctionsUC::getGetVar("preview","",UniteFunctionsUC::SANITIZE_KEY);
		$isPreviewMode = UniteFunctionsUC::strToBool($isPreviewMode);
		
		$addonConfig->startWithPreview($isPreviewMode);
		
		$this->isPreviewMode = $isPreviewMode;
		
		require EWPHelper::getPathTemplate("test_addon");
				
	}
	
	
}


$pathProviderAddon = GlobalsUC::$pathProvider."views/test_addon.php";

if(file_exists($pathProviderAddon) == true){
	require_once $pathProviderAddon;
	new UniteCreatorTestAddonViewProvider();
}
else{
	new UniteCreatorTestAddonView();
}
