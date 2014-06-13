<?php

namespace Blogger\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BlogCategoriesController extends Controller
{
    /**
     * @Route("/categories", name="blog_categories")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/add")
     * @Template()
     */
    public function addAction()
    {
    }

    /**
     * @Route("/edit")
     * @Template()
     */
    public function editAction()
    {
    }

}
