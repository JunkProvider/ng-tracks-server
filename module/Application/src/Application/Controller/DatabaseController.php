<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;
use Doctrine\ORM\Tools\SchemaTool;

class DatabaseController extends AbstractController
{
    /**
     * @return ViewModel
     */
    public function updateAction()
    {
    	try {
    		$classes = $this->getEntityManager()->getMetadataFactory()->getAllMetadata();
    		$this->getSchemaTool()->updateSchema($classes, true);
    		return $this->jsonResponse();
    	} catch (\Exception $e) {
    		return $this->jsonResponseFromException($e);
    	}
    }
    
    /**
     * @return ViewModel
     */
    public function dropAction()
    {
    	try {
    		$this->getSchemaTool()->dropDatabase();
    		return $this->jsonResponse();
    	} catch (\Exception $e) {
    		return $this->jsonResponseFromException($e);
    	}
    }
    
    /**
     * @return SchemaTool
     */
    private function getSchemaTool()
    {
        return $this->getContainer()->get(SchemaTool::class);
    }
}
