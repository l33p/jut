<?php
defined('_JEXEC') or die;
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class UbarControllerFile extends UbarController
{
	function UbarControllerFile()
	{
		parent::__construct();
		
		// Set format
		$this->json	= (JRequest::getCmd('format', 'html') == 'json');
		
		// Set upload info
		$this->setUploadData();
	}
	
	function setUploadData() {
		$this->returnURL	= index .'&view=mm&'. JUtility::getToken() .'=1';
		$this->uploadFolder	= JPATH_COMPONENT_SITE.DS.'assets'.DS.'img'.DS.'icons';
	}
	
	function upload()
	{
		if (!JRequest::checkToken('request')) {
			// 401 Unauthorized
			return $this->end(401, 'Invalid Token');
		}
		
		$this->file	= JRequest::getVar('Filedata', '', 'files', 'array');
		
		// TODO: Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');
		
		// Make the filename safe
		$this->file['name']	= strtolower(JFile::makeSafe($this->file['name']));
		
		if (empty($this->file['name'])) {
			return $this->end(400, 'Bad Request');
		}
		
		if (!$this->canUpload()) {
			return $this->end(415, 'Unsupported Media Type');
		}
		
		// Get full filename
		$name	= $this->uploadFolder.DS.$this->file['name'];
		
		// Check filename
		$name	= JPath::clean($name);
		if (JFile::exists($name)) {
			// 409 Conflict
			return $this->end(409, 'File already exists');
		}
		
		// Uplaod
		if (!JFile::upload($this->file['tmp_name'], $name)) {
			return $this->end(500, 'Could not upload file');
		}
		
		// Upload complete
		$this->end(200, 'File uploaded!');
	}
	
	// See administrator >> components >> com_media >> helpers >> media.php
	function canUpload()
	{
		// Check extension
		$ext	= strtolower(JFile::getExt($this->file['name']));
		$allow	= @explode(',', $this->getParam('upload_extensions', ''));
		$ignore	= @explode(',', $this->getParam('ignore_extensions', ''));
		if (!in_array($ext, $allow) && !in_array($ext, $ignore)) {
			return false;
		}
		
		// Check filesize
		$max	= (int) $this->getParam('upload_maxsize', 0);
		if ($max > 0 && (int) $this->file['size'] > $max) {
			return false;
		}
		
		// PHP image checks
		if ($this->getParam('restrict_uploads', 1))
		{
			$imgx	= explode(',', $this->getParam('image_extensions', ''));
			
			// GetImageSize
			if(in_array($ext, $imgx) && !getimagesize($this->file['tmp_name'])) {
				return false;
			}
			
			else if(!in_array($ext, $ignore))
			{
				$amime	= explode(',', $this->getParam('upload_mime', ''));
				$imime	= explode(',', $this->getParam('upload_mime_illegal', ''));
				
				// FileInfo
				if(function_exists('finfo_open') && $this->getParam('check_mime', 1))
				{
					$finfo	= finfo_open(FILEINFO_MIME);
					$type	= finfo_file($finfo, $this->file['tmp_name']);
					finfo_close($finfo);
					
					if(strlen($type) && !in_array($type, $amime) && in_array($type, $imime)) {
						return false;
					}
				}
				
				// MimeContentType
				else if(function_exists('mime_content_type') && $this->getParam('check_mime', 1))
				{
					$type	= mime_content_type($this->file['tmp_name']);
					if(strlen($type) && !in_array($type, $amime) && in_array($type, $imime)) {
						return false;
					}
				}
				
				// Check for usertype
				else
				{
					$user	= & JFactory::getUser();
					if(!$user->authorize('login', 'administrator')) {
						return false;
					}
				}
			}
		}
		
		// Cross-site scripting
		$xss	=  JFile::read($this->file['tmp_name'], false, 256);
		$tags	= array('abbr','acronym','address','applet','area','audioscope','base','basefont','bdo','bgsound','big','blackface','blink','blockquote','body','bq','br','button','caption','center','cite','code','col','colgroup','comment','custom','dd','del','dfn','dir','div','dl','dt','em','embed','fieldset','fn','font','form','frame','frameset','h1','h2','h3','h4','h5','h6','head','hr','html','iframe','ilayer','img','input','ins','isindex','keygen','kbd','label','layer','legend','li','limittext','link','listing','map','marquee','menu','meta','multicol','nobr','noembed','noframes','noscript','nosmartquotes','object','ol','optgroup','option','param','plaintext','pre','rt','ruby','s','samp','script','select','server','shadow','sidebar','small','spacer','span','strike','strong','style','sub','sup','table','tbody','td','textarea','tfoot','th','thead','title','tr','tt','ul','var','wbr','xml','xmp','!DOCTYPE', '!--');
		foreach($tags as $t)
		{
			if(stristr($xss, '<'. $t .' ') || stristr($xss, '<'. $t .'>')) {
				return false;
			}
		}
		
		// File passed the test!
		return true;
	}
	
	function delete()
	{
		$this->returnURL	.= '&delete=1';
		
		if (!JRequest::checkToken('request')) {
			return $this->end(401, 'Invalid Token');
		}
		
		// Get file name
		$file	= trim(JRequest::getVar('file', '', 'REQUEST', 'BASE64'));
		if (!strlen($file)) {
			return $this->end(400, 'Bar Request');
		} elseif (!$file = base64_decode($file)) {
			return $this->end(400, 'Bar Request');
		}
		
		// Get full file path
		$filepath	= $this->uploadFolder.DS.JFile::makeSafe($file);
		if (!JFile::exists($filepath)) {
			return $this->end(400, 'Bar Request');
		}
		
		// Delete
		if (!JFile::delete($filepath)) {
			return $this->end(400, 'Could not delete file');
		}
		
		$this->end(200, 'File deleted!');
	}
	
	function end($code, $msg = null)
	{
		// Flash
		if ($this->json)
		{
			$header	= 'HTTP/1.0 '. $code .' '. $msg;
			header( $header );
			jexit( $msg );
		}
		
		// No Flash
		else
		{
			// Redirect
			global $mainframe;
			$redirect	= $this->returnURL .'&msg='. $msg;
			$mainframe->redirect( $redirect );
		}
	}
	
	function getParam($name, $def = null)
	{
		static $params;
		
		// Get media params
		if (is_null($params))
		{
			jimport('joomla.application.component.helper');
			$params	= & JComponentHelper::getParams('com_media');
		}
		
		return $params->get($name, $def);
	}
}

