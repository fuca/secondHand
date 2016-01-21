<?php

namespace App\Components\FrontModule;
use \Nette\Application\UI\Control;
/**
 * @author Michal Fučík <michal.fuca.fucik(at)gmail.com>
 */
final class LogInControl extends Control {
    
    /** @var string templates dir */
    private $templatesDir;
    
    /** log in link target */
    private $logInTarget;
    
    /** @var string main template file */
    private $templateMain;
    
    /** @var string user dara template file */
    private $templateUser;
    
    /** @var string form template file */
    private $templateForm;
    
    /** @var Nette\Security\User */
    private $user;
    
    public function getUser() {
	if (!isset($this->user)) {
	    $this->user = $this->presenter->getUser();
        }
	return $this->user;
    }
    public function setLogInTarget($logInTarget) {
	$this->logInTarget = $logInTarget;
    }

    public function setTemplateMain($templateMain) {
	if (!file_exists($this->templatesDir . $templateMain)) {
	    throw new \Nette\FileNotFoundException("Template file with specified name does not exist");
        }
	$this->templateMain = $templateMain;
    }
    public function setTemplateUser($templateUser) {
	if (!file_exists($this->templatesDir . $templateUser)) {
	    throw new \Nette\FileNotFoundException("Template file with specified name does not exist");
        }
	$this->templateUser = $templateUser;
    }
    public function setTemplateForm($templateForm) {
	if (!file_exists($this->templatesDir . $templateForm)) {
	    throw new \Nette\FileNotFoundException("Template file with specified name does not exist");
        }
	$this->templateForm = $templateForm;
    }
    public function __construct(IContainer $parent = NULL, $name = NULL) {
	parent::__construct($parent, $name);
	$this->templatesDir = __DIR__ . "/templates/";
	$this->templateMain = $this->templatesDir . "default.latte";
	$this->templateForm = $this->templatesDir . "defaultForm.latte";
    }
    public function createComponentLoginForm($name) {
	$form = new LogInForm($this, $name, $this->presenter->translator);
	$form->initialize();
	return $form;
    }
    public function loginFormSuccessHandle($form) {
	$values = $form->getValues();
	if ($values->remember) {
	    $this->presenter->getUser()->setExpiration('14 days', FALSE);
	} else {
	    $this->presenter->getUser()->setExpiration('20 minutes', TRUE);
	}
	try {
	    $this->presenter->getUser()->login($values->username, $values->password);
	    $bl = $this->presenter->getParameter("backlink");
	    if ($bl) {
		$this->presenter->redirect($this->presenter->restoreRequest($bl));
	    } else {
		$this->presenter->redirect($this->logInTarget);
	    }
	} catch (AuthenticationException $e) {
	    $form->addError($this->getPresenter()->getTranslator()->translate($e->getMessage()));
	}
    }
    public function render() {
	$loggedIn = $this->getUser()->isLoggedIn();
	$this->template->setFile($this->templateMain);
	$this->template->logInLink = $this->presenter->link(":Front:Auth:in");
	$this->template->logOutLink = $this->presenter->link(":Front:Auth:out");
	$this->template->isLoggedIn = $loggedIn;
	$this->template->user = $loggedIn ? $this->getUser()->getIdentity() : null;
	$this->template->render();
    }
    public function renderForm() {
	$this->template->setFile($this->templateForm);
	$this->template->render();
    }
    public function renderUser() {
	$loggedIn = $this->getUser()->isLoggedIn();
	$this->template->setFile($this->templateUser);
	$this->template->isLoggedIn = $loggedIn;
	$this->template->user = $loggedIn ? $this->getUser()->getIdentity() : null;
	$this->template->userProfileLink = $this->presenter->link(":Front:User:profile");
	$this->template->render();
    }
}