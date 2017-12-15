<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DirectoryRepository")
 */
class Directory
{
    public function __construct($name, $parent, $user){
        $this->name = $name;
        $this->parent = $parent;
        $this->user = $user;

        return $this;
    }
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"group1", "group2"})
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"group1"})
     */
    private $name;

    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Directory", mappedBy="parent", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Directory", cascade={"persist", "remove"}, inversedBy="children")
     * @ORM\JoinColumn(nullable=true, name="parent_id", referencedColumnName="id")
     * @Groups({"group2"})
     */
    private $parent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\WebserviceUser")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


    public function getId() {
    	return $this->id;
    }


    public function getName() {
    	return $this->name;
    }


    public function getParent() {
    	return $this->parent;
    }

    public function setId($id) {
    	$this->id = $id;
    	return $this;
    }


    public function setName($name) {
    	$this->name = $name;
    	return $this;
    }


    public function setParent($parent) {
    	$this->parent = $parent;
    	return $this;
    }

    public function getChildren() {
        return $this->children;
    }

}
