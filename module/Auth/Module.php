<?php
namespace Auth;
use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Auth\Model\MyAuthStorage;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

 	public function getServiceConfig()
    	{
    		return array(
    				'factories'=>array(
    						'Auth\Model\MyAuthStorage' => function($sm){
    							return new \Auth\Model\MyAuthStorage('auth_storage');
     						},
    						 
    						'AuthService' => function($sm) {
    							//My assumption, you've alredy set dbAdapter
    							//and has users table with columns : user_name and pass_word
    							//that password hashed with md5
    							$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    							$dbTableAuthAdapter  = new DbTableAuthAdapter($dbAdapter,
    									'user','username','password', 'MD5(?)');
    							 
    							$authService = new AuthenticationService();
    							$authService->setAdapter($dbTableAuthAdapter);
    							$authService->setStorage($sm->get('Auth\Model\MyAuthStorage'));
    	
    							return $authService;
    						},
    				),
    		);
    	}
    	
    
    	
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
