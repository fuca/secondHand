<?php

namespace App\Model;

use \Doctrine\EntityManager,
    Nette\Caching\IStorage,
    Kdyby\Monolog\Logger;

/**
 * Description of BaseModel
 *
 * @author m.fucik
 */
abstract class BaseService {

    /** @var \Doctrine\EntityManager */
    public $entityManager;

    /** @var \Nette\Caching\IStorage */
    private $cacheStorage;

    /** @var \Kdyby\Monolog\Logger */
    protected $logger;

    /** @var string */
    private $className;

    protected function __construct(EntityManager $em, $entityClassName, Logger $logger = null) {
        if ($logger !== null) {
            $this->logger = $logger;
        }
        $this->entityManager = $em;
        $this->entityClassName = $entityClassName;
    }
}
