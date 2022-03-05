<?php
/**
 * @copyright	Copyright (c) 2022 facebooksimplemeta. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * system - facebooksimplemeta Plugin
 *
 * @package		Joomla.Plugin
 * @subpakage	facebooksimplemeta.facebooksimplemeta
 */
class plgsystemfacebooksimplemeta extends JPlugin {

	/**
	 * Constructor.
	 *
	 * @param 	$subject
	 * @param	array $config
	 */
	function __construct(&$subject, $config = array()) {
		// call parent constructor
		parent::__construct($subject, $config);
	}


	function onBeforeCompileHead() {
		$limit = $this->params->def('limitw');
		$locale = $this->params->def('locale');
		
			    
		$config = JFactory::getConfig();
                 $sitename = $config->get( 'sitename' );
			 
		    
		    
		
		if($limit<1) 
		    $limit = 247;
		else
		    $limit = $this->params->def('limitw')-3;
    
		$option = JRequest::getVar('option', '');
		$view = JRequest::getVar('view','');
		if($view=='article' && $option=='com_content') {
		    $db =  $database = JFactory::getDBO();
		    $document =& JFactory::getDocument();
		    $id = JRequest::getInt('id');
		    
		    $sql = "SELECT * FROM #__content WHERE id=".$id." LIMIT 1";
		    $db->setQuery($sql);		
		    $item = $db->loadObject();
		    
		    $narekovaji = array('"', "'");
		    $title = str_replace($narekovaji, '', $item->title);
		    
		   
	
		    if(isset($item->title))
			  $document->addCustomTag( "<meta property='og:title' content='". $title ."'>" );
			 
		    if(isset($item->introtext)) 
		    $document->addCustomTag( "<meta property='og:description' content='".  substr(strip_tags($item->introtext), 0, $limit)."...'>" );
		    $document->addCustomTag( "<meta property='og:url' content='".JURI::current()."'>" );
		    $document->addCustomTag( "<meta property='og:type' content='article'>" );
		     $document->addCustomTag( "<meta property='og:locale' content='". $locale ."'>" );
			$document->addCustomTag( "<meta property='og:site_name' content='". $sitename ."'>" );
		    
    
			  $images = json_decode($item->images);
    
		     
		    if (isset($images->image_intro) && !empty($images->image_intro)) {
			 $image = JUri::root() . $images->image_intro;
			 
		  } elseif (isset($images->image_full) && !empty($images->image_full)) {
			$image = JUri::root() . $images->image_full;
			
		  } else {
			preg_match_all('/<img[^>]+>/i', $this->item->introtext . $this->item->fulltext, $result);
			
			if (isset($result[0][0])) {
			$image = JUri::root() . $result[0][0];
			
		  } else {
		    $default_image_for_article = $this->params->get('default_image_for_article');
			
			 if ($default_image_for_article) {
			  $image = JUri::root() . $default_image_for_article;
			  }
		    // get logo
			 else {
			  $image = JUri::root() . $logo;
			}
		   }
		 }
	     
		 $document->addCustomTag( "<meta property='og:image' content='". $image ."'>" );
		 list($width, $height) = getimagesize($image);
    
		  $document->addCustomTag( "<meta property='og:image:width' content='".$width."'>" );
			  
	    
    
		}
	  }
	
}
