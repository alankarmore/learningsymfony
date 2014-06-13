<?php

namespace Blogger\BlogBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Blogger\BlogBundle\Entity\Blogs;

class BloggerBlogBundle extends Bundle
{
    /**
     * Boot function of our bundle.
     */
    public function boot()
    {
        Blogs::setUploadDir($this->container->getParameter("blogger.upload_dir"));
    }
}
