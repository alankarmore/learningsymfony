<?php

namespace Blogger\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Blog
 *
 * @ORM\Table(name="Blogs")
 * @ORM\Entity(repositoryClass="Blogger\BlogBundle\Entity\BlogsRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Blogs
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;

    /**
     *
     * @ORM\Column(name="blog", type="text")
     */
    protected $blog;
    
    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    protected $image;

    /**
     *
     * @ORM\Column(name="text")
     */
    protected $tags;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;
    
    /**
     * Upload direcotry
     * 
     * @var string 
     */
    protected static $uploadDir;
    
    public $file;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="blog",cascade="remove")
     */
    protected $comments;

    /**
     * @ORM\ManyToOne(targetEntity="Users",cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    
    public function __construct()
    {
        $this->comments = new ArrayCollection();
        
        $this->setCreatedAt(new \DateTime);
        $this->setUpdatedAt(new \DateTime);
    }
    
    /**
     * When trying to access an object as string
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }

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
     * Set title
     *
     * @param string $title
     * @return Blog
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * Set image
     *
     * @param string $image
     * @return Blog
     */
    public function setImage($image)
    {
        $this->image = $image;
    
        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set tags
     *
     * @param string $tags
     * @return Blog
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    
        return $this;
    }

    /**
     * Get tags
     *
     * @return string 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set comments
     *
     * @param string $comments
     * @return Blog
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    
        return $this;
    }

    /**
     * Get comments
     *
     * @return string 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Blog
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     * 
     * @param \DateTime $updatedAt
     * @return Blog
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set blog
     *
     * @param string $blog
     * @return Blogs
     */
    public function setBlog($blog)
    {
        $this->blog = $blog;
    
        return $this;
    }

    /**
     * Get blog
     *
     * @return string 
     */
    public function getBlog()
    {
        return $this->blog;
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedValue()
    {
       $this->setUpdatedAt(new \DateTime());
    }        

    /**
     * Add comments
     *
     * @param \Blogger\BlogBundle\Entity\Comment $comments
     * @return Blogs
     */
    public function addComment(\Blogger\BlogBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;
    
        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Blogger\BlogBundle\Entity\Comment $comments
     */
    public function removeComment(\Blogger\BlogBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }
    
 /**
     * Set upload dir path
     * 
     * @param string $dir
     */
    public static function setUploadDir($dir)
    {
        self::$uploadDir = $dir;
    }
 
    
    /**
     * Get upload dir path
     * 
     * @return string
     */
    public static function getUploadDir()
    {
        return self::$uploadDir;
    }
    
    
    public function getFile()
    {
        return $this->file;
    }
    
    public function getAbsolutePath()
    {
        return null === $this->image
            ? null
            : $this->getUploadRootDir().'/'.$this->image;
    }

    public function getWebPath()
    {
        return null === $this->image
            ? null
            : $this->getUploadDir().'/'.$this->image;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return $this->getUploadDir();
    }

    
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->getFile()->getClientOriginalName()
        );

        // set the path property to the filename where you've saved the file
        $this->image = $this->getFile()->getClientOriginalName();

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }
    
    /**
     * Get user id 
     * 
     * @return integer
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * Set user add
     * 
     * @param integer $user
     * @return \Blogger\BlogBundle\Entity\Blogs
     */
    public function setUser($user)
    {
        $this->user = $user;
        
        return $this;
    }
        
}