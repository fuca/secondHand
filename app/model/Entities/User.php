<?php

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author m.fucik
 * @ORM\Entity
 */
class User {

    use \Kdyby\Doctrine\Entities\Attributes\UniversallyUniqueIdentifier;
    use \Kdyby\Doctrine\Entities\MagicAccessors;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $surname;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $password;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;
    
    /**
     * @ORM\Column(type="Address")
     */
    protected $address;
    
    /**
     * @ORM\Column(type="Contact")
     */
    protected $contact;
    
    
}
