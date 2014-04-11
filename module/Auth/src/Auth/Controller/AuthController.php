<?php
namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Auth\Form\AuthForm;
use Auth\Model\User;

class AuthController extends AbstractActionController
{
	protected $form;
	protected $storage;
	protected $authservice;
	 
	public function getAuthService()
	{
 		if (! $this->authservice) {
 			$this->authservice = $this->getServiceLocator()
 			->get('AuthService');
 		}
		 
 		return $this->authservice;
	}
	 
	public function getSessionStorage()
	{
		if (! $this->storage) {
			$this->storage = $this->getServiceLocator()
			->get('Auth\Model\MyAuthStorage');
		}
		 
		return $this->storage;
	}
	 
	public function getForm()
	{	
		$form = new AuthForm();
		$user = new User();
		$request = $this->getRequest();
 		if ($request->isPost()) {
 			$user = new User();
 			$form->setInputFilter($user->getInputFilter());
 			$form->setData($request->getPost());
 			if(!$form->isValid()){
 				
 			}
 		}
		//return array('form' => $form);
 		return array(
 				'form'      => $form,
 				'messages'  => $this->flashmessenger()->getMessages()
 		);
		
	}
	 
	public function loginAction()
	{
		//if already login, redirect to success page
		if ($this->getAuthService()->hasIdentity()){
			return $this->redirect()->toRoute('album');
		}
		 
		return $this->form = $this->getForm();

 	}
	 
	public function authenticateAction()
	{
 		$redirect = 'auth';
 		$form = $this->getForm();
 		$request = $this->getRequest();
 	
  		$request = $this->getRequest();
  		if ($request->isPost()) {
 // 			$form->setData($request->getPost());
  			
//			if ($this->form->isValid()){
				//check authentication...
				$this->getAuthService()->getAdapter()
				->setIdentity($request->getPost('username'))
				->setCredential($request->getPost('password'));

				$result = $this->getAuthService()->authenticate();
				foreach($result->getMessages() as $message)
				{
					//save message temporary into flashmessenger
					$this->flashmessenger()->addMessage($message);
				}
				 
				if ($result->isValid()) {
					$redirect = 'album';
					//check if it has rememberMe :
					if ($request->getPost('rememberme') == 1 ) {
						$this->getSessionStorage()
						->setRememberMe(1);
						//set storage again
						$this->getAuthService()->setStorage($this->getSessionStorage());
					}
					$this->getAuthService()->getStorage()->write($request->getPost('username'));
				}
			//}
		}
		 
		return $this->redirect()->toRoute($redirect);
	}
	 
	public function logoutAction()
	{
		$this->getSessionStorage()->forgetMe();
		$this->getAuthService()->clearIdentity();
		 
		$this->flashmessenger()->addMessage("You've been logged out");
		return $this->redirect()->toRoute('auth');
	}
}