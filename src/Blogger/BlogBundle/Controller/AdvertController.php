<?php
// src/OC/PlatformBundle/Controller/AdvertController.php
namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Blogger\BlogBundle\Entity\Advert;

class AdvertController extends Controller
{
	public function indexAction($page)
  	{	
    // On ne sait pas combien de pages il y a
    // Mais on sait qu'une page doit être supérieure ou égale à 1
    	if ($page < 1) {
      	// On déclenche une exception NotFoundHttpException, cela va afficher
      	// une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
      		throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
    	}

    	// Ici, on récupérera la liste des annonces, puis on la passera au template

    	// Mais pour l'instant, on ne fait qu'appeler le template
    	return $this->render('BloggerBlogBundle:Advert:index.html.twig', array('listAdverts' => array()));
  	}

	public function viewAction($id)
  	{
    	$advert = array(
      'title'   => 'Recherche développpeur Symfony2',
      'id'      => $id,
      'author'  => 'Alexandre',
      'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
      'date'    => new \Datetime()
      );

      return $this->render('BloggerBlogBundle:Advert:view.html.twig', array(
      'advert' => $advert));
  	}

//  	public function addAction(Request $request)
//  	{
//    	$session = $request->getSession();
//    
//    	// Bien sûr, cette méthode devra réellement ajouter l'annonce
//    
//    	// Mais faisons comme si c'était le cas
//    	$session->getFlashBag()->add('info', 'Annonce bien enregistrée');
//
//    	// Le « flashBag » est ce qui contient les messages flash dans la session
//    	// Il peut bien sûr contenir plusieurs messages :
//    	$session->getFlashBag()->add('info', 'Oui oui, elle est bien enregistrée !');
//
//    	// Puis on redirige vers la page de visualisation de cette annonce
//    	return $this->redirectToRoute('blogger_blog_view', array('id' => 5));
// 	}

  	public function addAction(Request $request)
  {
    // Création de l'entité
    $advert = new Advert();
    $advert->setTitle('Recherche développeur Symfony2.');
    $advert->setAuthor('Alexandre');
    $advert->setContent("Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…");
    // On peut ne pas définir ni la date ni la publication,
    // car ces attributs sont définis automatiquement dans le constructeur

    // On récupère l'EntityManager
    $em = $this->getDoctrine()->getManager();

    // Étape 1 : On « persiste » l'entité
    $em->persist($advert);

    // Étape 2 : On « flush » tout ce qui a été persisté avant
    $em->flush();

    // Reste de la méthode qu'on avait déjà écrit
    if ($request->isMethod('POST')) {
      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
      return $this->redirect($this->generateUrl('blogger_blog_view', array('id' => $advert->getId())));
    }

    return $this->render('BloggerBlogBundle:Advert:add.html.twig');
  }

  	public function editAction($id, Request $request)
  	{
    	// Ici, on récupérera l'annonce correspondante à $id
      $advert = array(
      'title'   => 'Recherche développpeur Symfony2',
      'id'      => $id,
      'author'  => 'Alexandre',
      'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
      'date'    => new \Datetime()
    );

    return $this->render('BloggerBlogBundle:Advert:edit.html.twig', array(
      'advert' => $advert
    ));
    	// Même mécanisme que pour l'ajout
    	if ($request->isMethod('POST')) {
      		$request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

      		return $this->redirectToRoute('blogger_blog_view', array('id' => 5));
    	}

    	return $this->render('BloggerBlogBundle:Advert:edit.html.twig');
  	}

  	public function deleteAction($id)
  	{
    	// Ici, on récupérera l'annonce correspondant à $id

    	// Ici, on gérera la suppression de l'annonce en question

    	return $this->render('BloggerBlogBundle:Advert:delete.html.twig');
  	}

    public function menuAction()
    {
      // On fixe en dur une liste ici, bien entendu par la suite
      // on la récupérera depuis la BDD !
      $listAdverts = array(
      array(
        'title'   => 'Recherche développpeur Symfony2',
        'id'      => 1,
        'author'  => 'Alexandre',
        'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
        'date'    => new \Datetime()),
      array(
        'title'   => 'Mission de webmaster',
        'id'      => 2,
        'author'  => 'Hugo',
        'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
        'date'    => new \Datetime()),
      array(
        'title'   => 'Offre de stage webdesigner',
        'id'      => 3,
        'author'  => 'Mathieu',
        'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
        'date'    => new \Datetime())
    );

      return $this->render('BloggerBlogBundle:Advert:menu.html.twig', array('listAdverts' => $listAdverts));
    }
}