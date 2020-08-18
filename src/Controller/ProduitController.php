<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/Produit/liste", name="liste_produit")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $p=new Produit();
        $form=$this->createForm(ProduitType::class,
            $p,
            array('action'=>$this->generateUrl('add_produit'))
        );
        $data['form']=$form->createView();

        $data['produits'] = $em->getRepository(Produit::class)->findAll();
        return $this->render('produit/liste.html.twig', $data);
    }
    /**
     * @Route("/Produit/add", name="add_produit")
     */
    public function add(Request $request)
    {
        $p=new Produit();
        $form = $this->createForm(ProduitType::class, $p);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $p = $form->getData();
            $p->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($p);
            $em->flush();
        }
        return $this->redirectToRoute('liste_produit');
    }
    /**
     * @Route("/Produit/edit/{id}", name="edit_produit")
     */
    public function edit($id)
    {
        $em = $this->getDoctrine()->getManager();
        $p=$em->getRepository(Produit::class)->find($id);

        $form=$this->createForm(ProduitType::class,
            $p,
            array('action'=>$this->generateUrl('update_produit',['id'=>$id]))
        );
        $data['form']=$form->createView();

        $data['produits'] = $em->getRepository(Produit::class)->findAll();
        return $this->render('produit/liste.html.twig', $data);
    }
    /**
     * @Route("/Produit/update/{id}", name="update_produit")
     */
    public function update($id, Request $request)
    {
        $p=new Produit();
        $form = $this->createForm(ProduitType::class, $p);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $p = $form->getData();
            $p->setUser($this->getUser());
            $p->setId($id);
            //Récupération valeurs
            $em = $this->getDoctrine()->getManager();
            $produit=$em->getRepository(Produit::class)->find($p->getId());
            $produit->setLibelle($p->getLibelle());
            $em->flush();
        }
        return $this->redirectToRoute('liste_produit');
    }
    /**
     * @Route("/Produit/delete/{id}", name="delete_produit")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $p=$em->getRepository(Produit::class)->find($id);
        if($p!=null)
        {
            $em->remove($p);
            $em->flush();
        }
        return $this->redirectToRoute('liste_produit');
    }


}
