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

/**
 *
 * @author dfanchon
 * @category Rubedo
 * @package Rubedo
 */
class AuthenticationController extends AbstractController
{

    public function indexAction ()
    {
        $output = $this->params()->fromQuery();
        if (in_array('HTTPS', $output['site']['protocol'])) {
            $output['enforceHTTPS'] = true;
        } else {
            $output['enforceHTTPS'] = false;
        }
        $template = Manager::getService('FrontOfficeTemplates')->getFileThemePath("blocks/authentication.html.twig");
        $currentUser = Manager::getService('CurrentUser')->getCurrentUser();
        $output['currentUser'] = $currentUser;
        $css = array();
        $js = array(
            $this->getRequest()->getBasePath() . '/' . Manager::getService('FrontOfficeTemplates')->getFileThemePath("js/authentication.js")
        );
        return $this->_sendResponse($output, $template, $css, $js);
    }
}
