<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Terms;
use App\Form\CompanyType;
use App\Form\TermsType;
use App\Repository\CompanyRepository;
use App\Repository\TermsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 *
 * @package App\Controller
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
	
    /**
     * @Route("/dashboard", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
	
	/**
	 * @return Response
	 * @Route("/terms", name="system_config")
	 *
	 */
    public function termsConfig(Request $request, TermsRepository $termsRepository){
    	$all = $termsRepository->findAll ();
	    $terms =  new Terms();
    	if (count ($all) > 0){
    		$terms =  $all[0];
	    }
    	$form = $this->createForm (TermsType::class, $terms);
    	
    	$form->handleRequest ($request);
    	
    	if($form->isSubmitted () && $form->isValid ()){
    		$em = $this->getDoctrine ()->getManager ();
    		
    		$em->persist ($terms);
    		$em->flush ();
	    }
    	
    	return $this->render ('system/index.html.twig',[
    		'formTerms' => $form->createView ()
	    ]);
    }
	
	
	/**
	 * @param Request           $request
	 * @param CompanyRepository $repository
	 *
	 * @return Response
	 *
	 *@Route("/system", name="system_company")
	 */
    public function systemConfig(Request $request, CompanyRepository $repository){
    	$all = $repository->findAll ();
    	$company = new Company();
    	if (count ($all) > 0)
    		$company = $all[0];
    	
    	$form = $this->createForm (CompanyType::class, $company);
    	
    	$form->handleRequest ($request);
    	
    	if($form->isSubmitted () && $form->isValid ()){
		    $em = $this->getDoctrine ()->getManager ();
		
		    $em->persist ($company);
		    $em->flush ();
	    }
	    return $this->render ('system/systemConfig.html.twig',[
		    'formCompany' => $form->createView (),
		    'company' => $company
	    ]);
    }
}
