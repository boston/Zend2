<?php
namespace Auth\Form;

use Zend\Form\Form;

class AuthForm extends Form
{
	public function __construct($name = null)
	{
		// we want to ignore the name passed
		parent::__construct('auth');

		$this->add(array(
				'name' => 'username',
				'type' => 'Text',
				'options' => array(
						'label' => 'Identifiant : ',
				),
		));
		$this->add(array(
				'name' => 'password',
				'type' => 'Zend\Form\Element\Password',
				'options' => array(
						'label' => 'Mot de passe : ',
				),
		));
		$this->add(array(
				'name' => 'remember',
				'type' => 'Checkbox',
				'options' => array(
						'label' => 'Se souvenir de moi ',
				),
		));
		$this->add(array(
				'name' => 'submit',
				'type' => 'Submit',
				'attributes' => array(
						'value' => 'Me connecter',
						'id' => 'submitbutton',
				),
		));
	}
}