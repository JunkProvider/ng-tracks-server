<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Interop\Container\ContainerInterface;
use Doctrine\ORM\EntityRepository;
use Zend\Http\Response;

abstract class AbstractController extends AbstractActionController
{
	/**
	 * @var ContainerInterface
	 */
	private $container;
	
	/**
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

    /**
     * @param array $data
     * 
     * @return JsonModel
     */
    protected function jsonResponse($data = null)
    {
    	// header('Access-Control-Allow-Origin: *');
    	return new JsonModel([ 'data' => $data ]);
    }
    
    /**
     * @param array $data
     *
     * @return JsonModel
     */
    protected function jsonResponseFromException(\Exception $exception)
    {
    	/* @var Response $response */
    	$response = $this->getResponse();
    	$response->setStatusCode(Response::STATUS_CODE_500);
    	
    	return new JsonModel([ 'exception' => [
    		'message' => $exception->getMessage(),
    		'code' => $exception->getCode(),
    		'file' => $exception->getFile(),
    		'line' => $exception->getLine(),
    		'trace' => $exception->getTrace(),
    	]]);
    }
    
    /**
     * @return array
     */
    protected function getJsonPost()
    {
    	return json_decode($this->getRequest()->getContent(), true);
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get(EntityManager::class);
    }
    
    /**
     * @param string $className
     * 
     * @return EntityRepository
     */
    protected function getRepository($className)
    {
    	return $this->getEntityManager()->getRepository($className);
    }
    
    /**
     * @return ContainerInterface
     */
    protected function getContainer()
    {
    	return $this->container;
    }
}
