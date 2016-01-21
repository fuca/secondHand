<?php

namespace App\Model\AdminModule;

use App\Model\Entities\User,
    App\Model\BaseService,
    \Kdyby\Monolog\Logger,
    \Kdyby\Doctrine\EntityManager;

/**
 * @author Michal Fučík <michal.fuca.fucik(at)gmail.com>
 */
final class UserManager extends BaseService {

    public function __construct(EntityManager $em, Logger $logger) {
        parent::__construct($em, User::getClassName(), $logger);
    }

    public function createUser(User $user) {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
