<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Sortie;
use App\Form\SortieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    private $data;
    /**
     * @Route("/Sortie/liste", name="liste_sortie")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $s=new Sortie();
        $form=$this->createForm(SortieType::class,
            $s,
            array('action'=>$this->generateUrl('add_sortie'))
        );
        $this->data['form']=$form->createView();

        $this->data['sorties'] = $em->getRepository(Sortie::class)->findAll();
        return $this->render('sortie/liste.html.twig',$this->data);
    }
    /**
     * @Route("/Sortie/add", name="add_sortie")
     */
    public function add(Request $request)
    {
        $s=new Sortie();
        $form = $this->createForm(SortieType::class, $s);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $s = $form->getData();
            $s->setUser($this->getUser());
            if(($s->getProduit()->getQtStock())>=($s->getQtS()))
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($s);
                $em->flush();
                //Mise à jour de la quantité du produit
                $p=$em->getRepository(Produit::class)->find($s->getProduit()->getId());
                $p->setQtStock($p->getQtStock()-$s->getQtS());
                $em->flush();
            }
            else
            {
                $this->addFlash('error','Stock Insuffisant ('.$s->getProduit()->getQtStock().') vente impossible de('.$s->getQtS().') '.$s->getProduit().' !!!');
            }
        }
        return $this->redirectToRoute('liste_sortie');
    }
}
