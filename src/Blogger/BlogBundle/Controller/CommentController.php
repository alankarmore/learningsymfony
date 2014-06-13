<?php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Blogger\BlogBundle\Entity\Comment;
use Blogger\BlogBundle\Form\CommentType;

class CommentController extends Controller
{
    /**
     * @Route("/new/{id}", requirements={"id" = "\d+"})
     * @Template()
     */
    public function newAction($id)
    {
        $blog = $this->getBlog($id);

        $comment = new Comment();
        $comment->setBlog($blog);
        $form   = $this->createForm(new CommentType(), $comment);

        return $this->render('BloggerBlogBundle:Comment:new.html.twig', array(
            'comment' => $comment,
            'form'   => $form->createView()
        ));
    }
    
    /**
     * @Route("/create/{id}", requirements={"id" = "\d+"}, name="comment_create")
     * @Template()
     * 
     * @param integer $blog_id
     */
    public function createAction($id)
    {
        $blog = $this->getBlog($id);
        
        $user = $this->getDoctrine()
                     ->getManager()
                     ->getRepository("BloggerBlogBundle:Users")
                     ->find($this->get('session')->get('user')['userId']);
        
        $comment = new Comment();
        $comment->setBlog($blog);
        $comment->setUser($user);
        
        $request = $this->getRequest();
        $form = $this->createForm(new CommentType(), $comment);
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            
          return $this->redirect($this->generateUrl('blogs_show', array(
                'id' => $comment->getBlog()->getId())) .
                '#comment-' . $comment->getId()
            );            
        }
        
        return $this->render("BloggerBlogBundle:Comment:create.html.twig", array(
            'comment' => $comment,
            'form'    => $form->createView()
        ));
    }
    
    /**
     * Get blog according to the Id
     * 
     * @param integer $blog_id
     * @return Blogger/BlogBundle/Entity/Blogs
     * @throws NotFoundHttpException
     */
    protected function getBlog($blog_id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $blog = $em->getRepository('BloggerBlogBundle:Blogs')->find($blog_id);
        
        if (!$blog) {
            throw $this->createNotFoundException('Unable to find blog post');
        }
        
        return $blog;
    }
}