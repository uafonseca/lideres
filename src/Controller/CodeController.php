<?php

namespace App\Controller;

use App\Datatables\Tables\CodeDatatable;
use App\Entity\Book;
use App\Entity\Code;
use App\Entity\CodeSalesData;
use App\Entity\FinancialDetails;
use App\Form\CodeSalesType;
use App\Form\CodeType;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Exception;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * CodeController
 * @Route("/code")
 */
class CodeController extends AbstractController
{
	/** @var DatatableFactory */
	private $datatableFactory;

	/** @var DatatableResponse */
	private $datatableResponse;

	/**
	 * UserController constructor.
	 *
	 * @param DatatableFactory  $datatableFactory
	 * @param DatatableResponse $datatableResponse
	 */
	public function __construct(DatatableFactory $datatableFactory, DatatableResponse $datatableResponse)
	{
		$this->datatableFactory = $datatableFactory;
		$this->datatableResponse = $datatableResponse;
	}

	/**
	 * Method index
	 *
	 * @param Request $request [explicite description]
	 *
	 * @return Response
	 * 
	 * @Route("/", name="code_index")
	 */
	public function index(Request $request): Response
	{

		$datatable = $this->datatableFactory->create(CodeDatatable::class);

		$datatable->buildDatatable([
			'url' => $this->generateUrl('code_index')
		]);

		if ($request->isXmlHttpRequest() && $request->isMethod('POST')) {
			$this->datatableResponse->setDatatable($datatable);
			$this->datatableResponse->getDatatableQueryBuilder();

			return $this->datatableResponse->getResponse();
		}

		return $this->render('code/index.html.twig', [
			'datatable' => $datatable
		]);
	}

    /**
     * Method create
     *
     * @param Request $request [explicit description]
     *
     * @return JsonResponse
     *
     * @throws Exception
     * @Route("/new", name="code_create")
     *
     */
	public function create(Request $request): Response
    {
        $salesData = new CodeSalesData();
        $financeDetails = new FinancialDetails() ;
        $financeDetails
            ->setPaypalUrlComplete($this->generateUrl('paymentSuccess',[],UrlGeneratorInterface::ABSOLUTE_URL))
            ->setPaypalUrlCancel($this->generateUrl('paymentCancel',[],UrlGeneratorInterface::ABSOLUTE_URL));

        $salesData->setFinancialDetails($financeDetails);
		$defaultData = [
			'salesData' => $salesData
		];

		$form = $this->createFormBuilder($defaultData, [
			'action' => $this->generateUrl('code_create'),
			'attr' => ['novalidate' => 'novalidate']
		])
			->add('book', EntityType::class, [
				'label' => 'libro',
				'class' => Book::class,
				'query_builder' => function (EntityRepository $repository) {
					return $repository->createQueryBuilder('book');
				},
				'choice_label' => 'title',
				'placeholder' => '--SELECCIONE--',
                'required' => true
			])
			->add('starDate', null, ['label' => 'Fecha inicio', 'required' => true])
			->add('totalDays', NumberType::class, ['label' => 'Días de activación', 'required' => true])
			->add('total', NumberType::class, ['label' => 'Cantidad de códigos a generar', 'required' => true])
			->add('unlimited', CheckboxType::class, ['label' => 'Activación ilimitada', 'required' => false])
			->add('salesData',CodeSalesType::class, ['label' => false])
			->getForm();

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();

			for ($i = 0; $i < (int)$form->getData()['total']; $i++) {
				$code = new Code();
				$code->setCode(uniqid());
				$date = new DateTime($form->getData()['starDate']);
				$code
					->setSalesData($salesData)
					->setBook($em->getRepository(Book::class)->find($form->getData()['book']))
					->setCompany($this->getUser()->getCompany())
					->setStarDate(new DateTime($form->getData()['starDate']))
					->setMaxDays($form->getData()['totalDays'])
					->setUnlimited($form->getData()['unlimited']);

				if (!$code->getUnlimited()) {
					$code->setEndDate($date->modify("+{$form->getData()['totalDays']} days"));
				}
				$em->persist($code);
			}

			$em->flush();
            return $this->redirectToRoute('code_index');
		}

		return $this->render('code/new.html.twig', [
			'form' => $form->createView(),
		]);
	}
	
	/**
	 * Method onPaymentSuccess
	 *
	 * @return Response
     * 
     * @Route("/paymentSuccess", name="paymentSuccess")
	 */
	public function onPaymentSuccess():Response{
		return new Response('payment success');
	}
		
	/**
	 * Method onPaymentCancel
	 *
	 * @return Response
	 * 
	 * @Route("/paymentCancel", name="paymentCancel")
	 */
	public function onPaymentCancel():Response{
		return new Response('payment cancel');
	}
	
	/**
	 * Method removeCode
	 *
	 * @param Code $code [explicite description]
	 *
	 * @return JsonResponse
	 * 
	 *  @Route("/{uuid}/remove", name="code_delete")
	 */
	public function removeCode(Code $code){
		$em =$this->getDoctrine ()->getManager ();

		$em->remove($code);
		$em->flush ();

		return new JsonResponse([
			'type' => 'success',
			'message' => 'Código eliminado'
		]);
	}
}
