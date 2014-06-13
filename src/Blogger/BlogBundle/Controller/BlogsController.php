<?php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Blogger\BlogBundle\Entity\Blogs;
use Blogger\BlogBundle\Form\BlogsType;


class BlogsController extends Controller
{
    /**
     * @Route("/show/{id}", requirements={"id" = "\d+"},name="blogs_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em =  $this->getDoctrine()->getManager();
        
        $blog = $em->getRepository("BloggerBlogBundle:Blogs")->find($id);
        
        if (!$blog) {
            throw $this->createNotFoundException('We are not able to find the blog post');
        }
        
        $comments =  $em->getRepository('BloggerBlogBundle:Comment')
                        ->getCommentsForBlog($blog->getId());
        
        
        return $this->render('BloggerBlogBundle:Blogs:show.html.twig', array(
                'blog'     => $blog,
                'comments' => $comments
            ));
    }
    
    /**
     * @Route("/add", name="blogs_add")
     * @Template()
     */
    public function addAction(Request $request)
    {
        $blogs = new Blogs();
        
        $form = $this->createForm(new BlogsType(), $blogs);
        
        $form->handleRequest($request);
        
        if ($request->getMethod() == 'POST') {
        
            if ($form->isValid()) {
                $blogs->upload();
                $user = $this->getDoctrine()
                             ->getManager()
                             ->getRepository("BloggerBlogBundle:Users")
                             ->find($this->get('session')->get('user')['userId']);
                
                $blogs->setUser($user);
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($blogs);
                $em->flush();
                
                return $this->redirect($this->generateUrl('blog_page'));
            }
        }
        
        return $this->render('BloggerBlogBundle:Blogs:add.html.twig', array('form' => $form->createView()));
    }
    
    /**
     * @Route("/myblogs", name="my_blogs")
     * @Template()
     */
    public function myBlogsAction()
    {
        $user = $this->getDoctrine()
                     ->getManager()
                     ->getRepository("BloggerBlogBundle:Users")
                     ->find($this->get('session')->get('user')['userId']);
        
        $em = $this->getDoctrine()->getManager();
        $blogs = $em->getRepository("BloggerBlogBundle:Blogs")
                    ->getLatestBlogs($user);
        
        return $this->render('BloggerBlogBundle:Page:index.html.twig', 
                array('blogs' => $blogs));
 
    }
    
    /**
     * @Route("/delete/{id}",requirements={"id" = "\d+"}, name="blog_del")
     * @Method("GET")
     * @Template()
     */
    public function deleteAction(Request $request)
    {
        $blogId = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $blogEntity = $em->getRepository('BloggerBlogBundle:Blogs')->find($blogId);
        if ($blogEntity !== NULL) {
            unlink($blogEntity->getAbsolutePath());
            
            $em->remove($blogEntity);
            $em->flush();
        } 
        
        return $this->redirect($this->generateUrl('blog_page'));
    }
}