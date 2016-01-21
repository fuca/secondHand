<?php

namespace App\SecurityModule\Model;
use \Nette\Object,
    \Nette\DateTime,
    \Nette\Security\Identity,
    \Nette\Security\IAuthenticator,
    \Nette\Security\AuthenticationException,
    \Nette\Utils\Strings,
    \Kdyby\Monolog\Logger,
    \Nette\Security\Passwords,
    \App\Model\Misc\Exceptions,
    \App\UsersModule\Model\Service\IUserService,
    \App\Model\Service\IRoleService;
    
/**
 * Implementation of IAurhenticator. 
 *
 * @author Michal Fučík <michal.fuca.fucik(at)gmail.com>
 */
final class Authenticator extends Object implements IAuthenticator {
    /**
     * @var \App\Model\Service\IRoleService 
     */
    private $rolesService;
    
    /**
     * @var \App\UsersModule\Model\Service\IUserService 
     */
    private $usersService;
    /** @var Salt */
    private $salt;
    
    /**
     * @var \Kdyby\Monolog\Logger
     */
    private $logger;
    
    public function getLogger() {
	return $this->logger;
    }
    public function setLogger(Logger $logger) {
	$this->logger = $logger;
    }
    
    public function setSalt($salt) {
	$this->salt = $salt;
    }
    public function setRolesService(IRoleService $roleService) {
	$this->rolesService = $roleService;
    }
    public function setUsersService(IUserService $userService) {
	$this->usersService = $userService;
    }
    /**
     * @param Credentials  Prihlasovaci udaje.
     * @throws AuthenticationException Chyba v overeni udaju.
     * @return Identitu uzivatele.
     */
    public function authenticate(array $credentials) {
	list($username, $password) = $credentials;
	try {
	    $user = $this->usersService->getUserEmail($username);
	} catch (Exceptions\NoResultException $ex) {
	    $this->getLogger()->addAlert("### ATTEMPT TO LOG IN WITH INVALID EMAIL ### - exception = ".$ex);
	    throw new AuthenticationException("securityModule.loginControl.messages.invalidCredentials", self::IDENTITY_NOT_FOUND);
	}
	    
	if (!Passwords::verify($password, $user->password)) {
	    $this->getLogger()->addAlert("### ATTEMPT TO LOG IN WITH INVALID PASSWORD ### - tried password = ".$password);
	    throw new AuthenticationException("securityModule.loginControl.messages.invalidCredentials", self::INVALID_CREDENTIAL);
	}
	
	if (!$user->active) {
	    $this->getLogger()->addAlert("### ATTEMPT TO LOG TO INACTIVE ACCOUNT ### - account id = ".$user->getId());
	    throw new AuthenticationException("securityModule.loginControl.messages.userUnactive");
	}
	$this->usersService->updateLastLogin($user);
	$identity = $user;
	return $identity;
    }
}