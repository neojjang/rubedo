<?php
/**
 * Rubedo -- ECM solution
 * Copyright (c) 2013, WebTales (http://www.webtales.fr/).
 * All rights reserved.
 * licensing@webtales.fr
 *
 * Open Source License
 * ------------------------------------------------------------------------------------------
 * Rubedo is licensed under the terms of the Open Source GPL 3.0 license. 
 *
 * @category   Rubedo
 * @package    Rubedo
 * @copyright  Copyright (c) 2012-2013 WebTales (http://www.webtales.fr)
 * @license    http://www.gnu.org/licenses/gpl.html Open Source GPL 3.0 license
 */
namespace Rubedo\Blocks\Controller;

Use Rubedo\Services\Manager;
use \MongoGridFSFile;

/**
 *
 * @author dfanchon
 * @category Rubedo
 * @package Rubedo
 */
class AudioController extends AbstractController
{

    public function indexAction ()
    {
        $blockConfig = $this->params()->fromQuery('block-config', array());
        $output = $this->params()->fromQuery();
        $output['audioAutoPlay'] = isset($blockConfig['audioAutoPlay']) ? $blockConfig['audioAutoPlay'] : false;
        $output['audioPreload'] = isset($blockConfig['audioPreload']) ? $blockConfig['audioPreload'] : false;
        $output['audioControls'] = isset($blockConfig['audioControls']) ? $blockConfig['audioControls'] : true;
        $output['audioLoop'] = isset($blockConfig['audioLoop']) ? $blockConfig['audioLoop'] : false;
        $output['audioFile'] = isset($blockConfig['audioFile']) ? $blockConfig['audioFile'] : null;
        $output['alternativeMediaArray'] = array();
        if ($output['audioFile']) {
            $media = Manager::getService('Dam')->findById($output['audioFile']);
            $output['contentType'] = $media['Content-Type'];
            
            $mainFile = Manager::getService('Files')->findById($media['originalFileId']);
            if (! $mainFile instanceof MongoGridFSFile) {
                throw new \Rubedo\Exceptions\NotFound("No Image Found", "Exception8");
            }
            $meta = $mainFile->file;
            $filename = $meta['filename'];
            
            $output['extension'] = pathinfo($filename, PATHINFO_EXTENSION);
            
            $output['alt'] = isset($media['fields']['alt']) ? $media['fields']['alt'] : '';
        }
        
        $template = Manager::getService('FrontOfficeTemplates')->getFileThemePath("blocks/audio.html.twig");
        
        $css = array();
        $js = array(
            '/components/longtailvideo/jwplayer/jwplayer.js',
            $this->getRequest()->getBasePath() . '/' . Manager::getService('FrontOfficeTemplates')->getFileThemePath("js/video.js")
        );
        return $this->_sendResponse($output, $template, $css, $js);
    }
}
