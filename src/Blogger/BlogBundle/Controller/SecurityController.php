<?php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;
use Blogger\BlogBundle\Entity\Users;
use Blogger\BlogBundle\Form\UsersType;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
     * @Route("/login",name="blogs_login")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        $session = $this->get('session'); 
        
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
            'error'    => $error
        );
    }
    
    /**
     * @Route("/check", name="blogs_login_check")
     * 
     */
    public function checkAction(Request $request)
    {
        $users = new Users();
        $session = $this->get('session');
        
        if ($request->getMethod() == 'POST') {
            if ($request->get('username') &&  $request->get('password')) {
                $password = $request->get('password');
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($users);
                $encodedPassword = $encoder->encodePassword($password, '');

                if (!$encoder->isPasswordValid($encodedPassword, $password, '')) {
                    // redirecting user to login again with error.
                    return $this->redirect($this->generateUrl("blogs_login"));
                } else {
                    $users->setUsername($request->get('username'));
                    $users->setPassword($encodedPassword);

                    // checking is user is valid according to the username and password
                    $em = $this->getDoctrine()->getRepository('BloggerBlogBundle:Users');
                    $validUser = $em->isValidUser($users);
                    if ($validUser !== null) {
                        $userdata = array();
                        $userdata['userId'] = $validUser->getId();
                        $userdata['firstName'] = $validUser->getFirstName();
                        $userdata['lastName'] = $validUser->getLastName();
                        $session->set('user',$userdata);

                        return $this->redirect($this->generateUrl("blog_page"));
                    }
                }
            } else {
                
               return $this->redirect($this->generateUrl('blogs_login'));
            }
        } else {
            return $this->redirect($this->generateUrl("blogs_login"));
        }
    }
    
    /**
     * @Route("/register",name="blogs_register")
     * @Template()
     */
    public function registerAction(Request $request)
    {
        $users = new Users();
        
        $registrationForm = $this->createForm(new UsersType(), $users);
        
        if ($request->getMethod() == 'POST') {
            $registrationForm->handleRequest($request);
            if ($registrationForm->isValid()) {
                $encoderFactory = $this->get('security.encoder_factory');
                $encoder = $encoderFactory->getEncoder($users);
                
                // generating password 
                $password = $encoder->encodePassword($registrationForm['password']->getData(),'');
                
                if (!$encoder->isPasswordValid($password,$registrationForm['password']->getData(),'')) {
                    // redirecting user to registration again with error.
                    return $this->render("BloggerBlogBundle:Security:register.html.twig", array(
                        'form' => $registrationForm->createView(),
                        'error' => 'Registration not successful please register your self again.'
                    ));
                } else {
                    $users->setPassword($password);
                }
                
                // saving user details in the database.
                $em = $this->getDoctrine()->getManager();
                $em->persist($users);
                $em->flush();
                
                // redirecting to the login page.
                return $this->redirect($this->generateUrl("blogs_login"));
            }
        }
        
        return $this->render("BloggerBlogBundle:Security:register.html.twig", array(
            'form' => $registrationForm->createView()
        ));
    }
    
    /**
     * @Route("/sessionout", name="blogs_logout")
     */
    public function logoutAction()
    {
        $this->get('session')->clear();
        
        return $this->redirect($this->generateUrl("blog_page"));
    }
}