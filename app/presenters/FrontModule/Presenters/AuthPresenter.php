<?php 

namespace App\SecurityModule\Presenters;
use \Nette\Application\UI\Form,
    \Nette\Security\AuthenticationException,
    \App\SystemModule\Presenters\BasePresenter,
    \App\SystemModule\Forms\LogInForm,
    \Kdyby\Monolog\Logger;

/**
 * Authorization presenter
 * @author Michal Fučík <michal.fuca.fucik(at)gmail.com>
 */
class AuthPresenter extends BasePresenter {
    
    /**
     * @inject 
     * @var \App\UsersModule\Model\Service\IUserService
     */
    public $users;
    
    public function actionDefault() {
	$this->redirect("in");
    }
    
    public function actionIn() {
    }
    public function actionOut() {
	$this->getUser()->logout(TRUE);
	//$this->flashMessage($this->tt("securityModule.loginControl.messages.uWereLoggedOut"), self::FM_INFO);
	$this->redirect('in');
    }
}