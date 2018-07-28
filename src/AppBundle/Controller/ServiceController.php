<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AvailableService;
use AppBundle\Form\Type\ServiceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ServiceController extends Controller
{
    /**
     * @Route("/pricing", name="pricing")
     * @Template("pricing.html.twig")
     */
    public function editPricesAction()
    {
        $services = $this->getDoctrine()->getRepository("AppBundle:AvailableService")->findAll();

        $forms = [];
        foreach ($services as $service) {
            $forms[] = $this->createForm(ServiceType::class, $service, [
                'action' => $this->generateUrl('save_price', ['id' => $service->getId()]),
                'method' => 'POST'
            ]);
        }

        return [
            'forms' => array_map(function(FormInterface $f) {
                return $f->createView();
            }, $forms)
        ];
    }

    /**
     * @Route("/pricing/{id}/save", name="save_price")
     */
    public function savePriceAction(Request $request, AvailableService $serviceType)
    {
        $form = $this->createForm(ServiceType::class, $serviceType);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($serviceType);
            $em->flush();
        }

        return $this->redirectToRoute('pricing');
    }
}