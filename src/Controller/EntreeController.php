<?php

namespace App\Controller;

use App\Entity\Entree;
use App\Entity\Produit;
use App\Form\EntreeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EntreeController extends AbstractController
{
    /**
     * @Route("/Entree/liste", name="liste_entree")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $e=new Entree();
        $form=$this->createForm(EntreeType::class,
            $e,
            array('action'=>$this->generateUrl('add_entree'))
        );
        $data['form']=$form->createView();

        $data['entrees'] = $em->getRepository(Entree::class)->findAll();

        return $this->render('entree/liste.html.twig',$data);
    }
    /**
     * @Route("/Entree/add", name="add_entree")
     */
    public function add(Request $request)
    {
        $e=new Entree();
        $form = $this->createForm(EntreeType::class, $e);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $e = $form->getData();
            $e->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($e);
            $em->flush();
            //Mise à jour de la quantité du produit
            $p=$em->getRepository(Produit::class)->find($e->getProduit()->getId());
            $p->setQtStock($p->getQtStock()+$e->getQtE());
            $em->flush();
        }
        return $this->redirectToRoute('liste_entree');
    }
}
