<?php

namespace Acme\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Blog
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Acme\TestBundle\Entity\BlogRepository")
 */
class Blog
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="blog_title", type="string", length=255)
     */
    private $blogTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="added_on", type="datetime")
     */
    private $addedOn;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status = true;

    /**
     * @var string 
     * 
     * @ORM\Column(name="attachment", type="string")
     */
    private $attachment;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set blogTitle
     *
     * @param string $blogTitle
     * @return Blog
     */
    public function setBlogTitle($blogTitle)
    {
        $this->blogTitle = $blogTitle;
    
        return $this;
    }

    /**
     * Get blogTitle
     *
     * @return string 
     */
    public function getBlogTitle()
    {
        return $this->blogTitle;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Blog
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set addedOn
     *
     * @param \DateTime $addedOn
     * @return Blog
     */
    public function setAddedOn($addedOn)
    {
        $this->addedOn = $addedOn;
    
        return $this;
    }

    /**
     * Get addedOn
     *
     * @return \DateTime 
     */
    public function getAddedOn()
    {
        return $this->addedOn;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Blog
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * 
     * @return string
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * 
     * @param string $attachment
     */
    public function setAttachment($attachment)
    {
        $this->attachment = $attachment;
    }


}
