<?php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Blogger\BlogBundle\Entity\Enquiry;
use Blogger\BlogBundle\Form\EnquiryType;

class PageController extends Controller
{
    /**
     * @Route("/", name="blog_page")
     * @Template()
     */
    public function indexAction()
    {        
        $em = $this->getDoctrine()->getManager();
        $blogs = $em->getRepository("BloggerBlogBundle:Blogs")->getLatestBlogs();
        
        return $this->render('BloggerBlogBundle:Page:index.html.twig', 
                array('blogs' => $blogs));
    }

    /**
     * @Route("/about", name="blog_about")
     * @Template()
     */
    public function aboutAction()
    {
        return array();
    }

    /**
     * @Route("/contact", name="blog_contact")
     * @Template()
     */
    public function contactAction()
    {
        $enquiry = new Enquiry();
        $contactForm = $this->createForm(new EnquiryType(), $enquiry);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $contactForm->handleRequest($request);
            
            if ($contactForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($enquiry);
                $em->flush();
                
                // now sending mail to blog admin 
                $emailMessage = \Swift_Message::newInstance()
                                ->setSubject("Contact enquiry from alanblog")
                                ->setFrom('alankar.more@gmail.com')
                                ->setTo($this->container->getParameter('blogger_blog.emails.contact_email'))
                                ->setBody($this->renderView('BloggerBlogBundle:Page:contactEmail.txt.twig', array('enquiry' => $enquiry)));
                
                $this->get('Mailer')->send($emailMessage);
                
                $this->get('session')->getFlashBag()->add('blogger-notice', 'Contact request has been sent. Keep blogging!');
                        
                return $this->redirect($this->generateUrl("blog_contact"));
            }
        }
        
        return array('form' => $contactForm->createView());
    }
}
