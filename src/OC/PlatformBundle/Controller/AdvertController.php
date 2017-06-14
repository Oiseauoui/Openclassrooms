<?php
// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

// N'oubliez pas ces use
use OC\PlatformBundle\Entity\AdvertSkill;
use OC\PlatformBundle\Event\MessagePostEvent;
use OC\PlatformBundle\Event\PlatformEvents;
use OC\PlatformBundle\Form\AdvertType;
use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Image;
use OC\PlatformBundle\Entity\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\HttpFoundation\RedirectResponse; // N'oubliez pas ce use
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
// N'oubliez pas ce use pour l'annotation
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;



class AdvertController extends Controller
{
    /**
     * @Security("has_role('ROLE_AUTEUR')")
     */

    // La route fait appel à OCPlatformBundle:Advert:view,
    // on doit donc définir la méthode viewAction.
    // On donne à cette méthode l'argument $id, pour
    // correspondre au paramètre {id} de la route
    //  public function viewAction($id)
    //   {
    // $id vaut 5 si l'on a appelé l'URL /platform/advert/5

    // Ici, on récupèrera depuis la base de données
    // l'annonce correspondant à l'id $id.
    // Puis on passera l'annonce à la vue pour
    // qu'elle puisse l'afficher

    //     return new Response("Affichage de l'annonce d'id : ".$id);
    //   }

    // ... et la méthode indexAction que nous avons déjà créée

    // On récupère tous les paramètres en arguments de la méthode

//}
    public function indexAction($page)
    {
        /*
         *  // https://openclassrooms.com/courses/developpez-votre-site-web-avec-le-framework-symfony/le-routeur-de-symfony
            // On veut avoir l'URL de l'annonce d'id 5.
                $url = $this->get('router')->generate(
                    'oc_platform_view', // 1er argument : le nom de la route
                    array('id' => 5)    // 2e argument : les valeurs des paramètres
                );
                // $url vaut « /platform/advert/5/ »

                return new Response("L'URL de l'annonce d'id 5 est : ".$url);
            }
            public function viewSlugAction($slug, $year, $format)
            {
                return new Response(
                    "On pourrait afficher l'annonce correspondant au
                    slug '".$slug."', créée en ".$year." et au format ".$format."."
                );
            }
        */
//https://openclassrooms.com/courses/developpez-votre-site-web-avec-le-framework-symfony/les-controleurs-avec-symfony
       // return new Response("Hello World !");

        // On ne sait pas combien de pages il y a
        // Mais on sait qu'une page doit être supérieure ou égale à 1
       if ($page < 1) {
            // On déclenche une exception NotFoundHttpException, cela va afficher
            // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
           throw new NotFoundHttpException
           ('Page "'.$page.'" inexistante.');
        }
        $nbPerPage = 3;

        // Ici, on récupérera la liste des annonces, puis on la passera au template

 //https://openclassrooms.com/courses/developpez-votre-site-web-avec-le-framework-symfony/les-services-theorie-et-creation-1
        // On a donc accès au conteneur :

       //$mailer = $this->container->get('mailer');

        // On peut envoyer des e-mails, etc.

        //https://openclassrooms.com/courses/developpez-votre-site-web-avec-le-framework-symfony/le-moteur-de-templates-twig-1
        // Mais pour l'instant, on ne fait qu'appeler le template
        // Dans l'action indexAction() :

        // Notre liste d'annonce en dur
    /*  $listAdverts = array(
            array(
                'title'   => 'Recherche développpeur Symfony',
                'id'      => 1,
                'author'  => 'Alexandre',
                'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
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


        );*/


//https://openclassrooms.com/courses/developpez-votre-site-web-avec-le-framework-symfony/tp-consolidation-de-notre-code

        // Pour récupérer la liste de toutes les annonces : on utilise findAll()
        $listAdverts = $this->getDoctrine()
            ->getManager()
            ->getRepository('OCPlatformBundle:Advert')
            //->findAll()
            ->getAdverts($page, $nbPerPage)

        ;
        // On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
        $nbPages = ceil(count($listAdverts) / $nbPerPage);

        // Si la page n'existe pas, on retourne une 404
        if ($page > $nbPages) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }


        // Et modifiez le 2nd argument pour injecter notre liste
        return $this->render
        ('OCPlatformBundle:Advert:index.html.twig',
            array(
            'listAdverts' => $listAdverts,
            'nbPages'     => $nbPages,
            'page'        => $page,

        ));

    }


    // On injecte la requête dans les arguments de la méthode
    // On modifie viewAction, car elle existe déjà

        public function viewAction($id)
        {
            /*
            // Récupération de la session
            $session = $request->getSession();

            // On récupère le contenu de la variable user_id
            $userId = $session->get('user_id');

            // On définit une nouvelle valeur pour cette variable user_id
            $session->set('user_id', 91);

            // On n'oublie pas de renvoyer une réponse
            return new Response("<body>Je suis une page de test, je n'ai rien à dire</body>");
*/
            /*
             // Créons nous-mêmes la réponse en JSON, grâce à la fonction json_encode()
             $response = new Response(json_encode(array('id' => $id)));

             // Ici, nous définissons le Content-type pour dire au navigateur
             // que l'on renvoie du JSON et non du HTML
             $response->headers->set('Content-Type', 'application/json');

             return $response;
 */

            //return $this->redirectToRoute('oc_platform_home'); // редирект

            //$url = $this->get('router')->generate('oc_platform_home');

           // return new RedirectResponse($url);

            // On crée la réponse sans lui donner de contenu pour le moment
       /*     $response = new Response();

            // On définit le contenu
            $response->setContent("Ceci est une page d'erreur 404");

            // On définit le code HTTP à « Not Found » (erreur 404)
            $response->setStatusCode(Response::HTTP_NOT_FOUND);

            // On retourne la réponse
         //   return $response;
           //  }

           return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
                'id' => $id
            ));*/
   //     }

            // Ici, on récupérera l'annonce correspondante à l'id $id
// On récupère le repository
            $em = $this->getDoctrine()->getManager();

               // On récupère l'annonce $id
    $advert = $em->getRepository('OCPlatformBundle:Advert')
                  ->find($id);

    if (null === $advert) {
        throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    // On récupère la liste des candidatures de cette annonce
    $listApplications = $em
        ->getRepository('OCPlatformBundle:Application')
        ->findBy(array('advert' => $advert))
    ;
    //https://openclassrooms.com/courses/developpez-votre-site-web-avec-le-framework-symfony/les-relations-entre-entites-avec-doctrine2-1

            // On récupère maintenant la liste des AdvertSkill
            $listAdvertSkills = $em
                ->getRepository('OCPlatformBundle:AdvertSkill')
                ->findBy(array('advert' => $advert))
            ;

            return $this->render('OCPlatformBundle:Advert:view.html.twig',
                array(
                'advert'           => $advert,
                'listApplications' => $listApplications,
                'listAdvertSkills' => $listAdvertSkills
            ));
        }


    // Ajoutez cette méthode :

    /**
     * @Security("has_role('ROLE_AUTEUR')")
     */
   public function addAction(Request $request)
    {
       /* // On vérifie que l'utilisateur dispose bien du rôle ROLE_AUTEUR
        if (!$this->get('security.context')->isGranted('ROLE_AUTEUR')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux auteurs.');
        }

*/
        /* $session = $request->getSession();

         // Bien sûr, cette méthode devra réellement ajouter l'annonce

         // Mais faisons comme si c'était le cas
         $session->getFlashBag()->add('info', 'Annonce bien enregistrée');

         // Le « flashBag » est ce qui contient les messages flash dans la session
         // Il peut bien sûr contenir plusieurs messages :
         $session->getFlashBag()->add('info', 'Oui oui, elle est bien enregistrée !');

         // Puis on redirige vers la page de visualisation de cette annonce
         return $this->redirectToRoute('oc_platform_view', array('id' => 5));

         }
    */
// La gestion d'un formulaire est particulière, mais l'idée est la suivante :

       // Si la requête est en POST, c'est que le visiteur a soumis le formulaire
  /*     if ($request->isMethod('POST')) {
           // Ici, on s'occupera de la création et de la gestion du formulaire

           $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

           // Puis on redirige vers la page de visualisation de cettte annonce
           return $this->redirectToRoute('oc_platform_view', array('id' => 5));
       }

       // Si on n'est pas en POST, alors on affiche le formulaire
       return $this->render('OCPlatformBundle:Advert:add.html.twig');

*/
       //https://openclassrooms.com/courses/developpez-votre-site-web-avec-le-framework-symfony/les-services-theorie-et-creation-1
       // On récupère le service
     /*  $antispam = $this->container->get('oc_platform.antispam');

       // Je pars du principe que $text contient le texte d'un message quelconque
       $text = '...';
       if ($antispam->isSpam($text)) {
           throw new \Exception('Votre message a été détecté comme spam !');
       }
     */

       // Ici le message n'est pas un spam

// On récupère l'EntityManager
       $em = $this->getDoctrine()->getManager();

//https://openclassrooms.com/courses/developpez-votre-site-web-avec-le-framework-symfony/manipuler-ses-entites-avec-doctrine2-1
       // Création de l'entité
       $advert = new Advert();
       // On crée le FormBuilder grâce au service form factory

         $advert->setTitle('Recherche développeur Symfony.');
         $advert->setAuthor('Alexandre');
         $advert->setContent("Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…");
         // On peut ne pas définir ni la date ni la publication,
         // car ces attributs sont définis automatiquement dans le constructeur

  //https://openclassrooms.com/courses/developpez-votre-site-web-avec-le-framework-symfony/les-relations-entre-entites-avec-doctrine2-1
         // Création de l'entité Image
         $image = new Image();
         $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
         $image->setAlt('Job de rêve');

         // On lie l'image à l'annonce
         $advert->setImage($image);

         // Création d'une première candidature
         $application1 = new Application();
         $application1->setAuthor('Marine');
         $application1->setContent("J'ai toutes les qualités requises.");

         // Création d'une deuxième candidature par exemple
         $application2 = new Application();
         $application2->setAuthor('Pierre');
         $application2->setContent("Je suis très motivé.");

         // On lie les candidatures à l'annonce
         $application1->setAdvert($advert);
         $application2->setAdvert($advert);

   //https://openclassrooms.com/courses/developpez-votre-site-web-avec-le-framework-symfony/les-relations-entre-entites-avec-doctrine2-1
  // On récupère toutes les compétences possibles
        $listSkills = $em->getRepository('OCPlatformBundle:Skill')
             ->findAll();

         // Pour chaque compétence
         foreach ($listSkills as $skill) {
             // On crée une nouvelle « relation entre 1 annonce et 1 compétence »
             $advertSkill = new AdvertSkill();

             // On la lie à l'annonce, qui est ici toujours la même
             $advertSkill->setAdvert($advert);
             // On la lie à la compétence, qui change ici dans la boucle foreach
             $advertSkill->setSkill($skill);

             // Arbitrairement, on dit que chaque compétence est requise au niveau 'Expert'
             $advertSkill->setLevel('Expert');

             // Et bien sûr, on persiste cette entité de relation, propriétaire des deux autres relations
             $em->persist($advertSkill);
         }

         // Doctrine ne connait pas encore l'entité $advert. Si vous n'avez pas défini la relation AdvertSkill

         // On récupère l'EntityManager
       //  $em = $this->getDoctrine()->getManager();

         // Étape 1 : On « persiste » l'entité
         $em->persist($advert);

         // Étape 1 bis : si on n'avait pas défini le cascade={"persist"},
         // on devrait persister à la main l'entité $image
         // $em->persist($image);

         // Étape 1 ter : pour cette relation pas de cascade lorsqu'on persiste Advert, car la relation est
         // définie dans l'entité Application et non Advert. On doit donc tout persister à la main ici.
         $em->persist($application1);
         $em->persist($application2);

         // Étape 2 : On déclenche l'enregistrement
         $em->flush();


       // On crée le FormBuilder grâce au service form factory
       $form   = $this->get('form.factory')
           ->create(AdvertType::class, $advert);

       if ($form->handleRequest($request)->isValid()) {
           // On crée l'évènement avec ses 2 arguments
           $event = new MessagePostEvent($advert->getContent(), $advert->getUser());

           // On déclenche l'évènement
           $this->get('event_dispatcher')->dispatch(PlatformEvents::POST_MESSAGE, $event);

           // On récupère ce qui a été modifié par le ou les listeners, ici le message
           $advert->setContent($event->getMessage());

       }

       if ($request->isMethod('POST') && $form
               ->handleRequest($request)->isValid()) {
          // Ajoutez cette ligne :
           // c'est elle qui déplace l'image là où on veut les stocker
           $advert->getImage()->upload();


           $em = $this->getDoctrine()->getManager();
           $em->persist($advert);
           $em->flush();

           $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

           return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
       }

       // Si on n'est pas en POST, alors on affiche le formulaire
       return $this->render('OCPlatformBundle:Advert:add.html.twig',
           array (

                   'form' => $form->createView(),

           ));
//

       // Ici l'utilisateur a les droits suffisant,
       // on peut ajouter une annonce

   }


    public function editAction($id, Request $request)
    {
 //https://openclassrooms.com/courses/developpez-votre-site-web-avec-le-framework-symfony/les-relations-entre-entites-avec-doctrine2-1
        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }


        $form = $this->get('form.factory')->create(AdvertEditType::class, $advert);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            // Inutile de persister ici, Doctrine connait déjà notre annonce
            $em->remove($advert);
            $em->flush();

//            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');
            $request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée.");

         return $this->redirectToRoute('oc_platform_view',
                array
                    //('id' => 5));
                ('id' => $advert->getId()));
        }

     /*   $advert = array(
            'title' => 'Recherche développpeur Symfony',
            'id' => $id,
            'author' => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
            'date' => new \Datetime()
        );*/

        return $this->render('OCPlatformBundle:Advert:
        edit.html.twig', array(
            'advert' => $advert,
            'form' =>$form->createView(),
        ));
    }

    public function deleteAction(Request $request, $id)
    {
 //https://openclassrooms.com/courses/developpez-votre-site-web-avec-le-framework-symfony/les-relations-entre-entites-avec-doctrine2-1
        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id
        $advert = $em->getRepository('OCPlatformBundle:Advert')
            ->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        // On boucle sur les catégories de l'annonce pour les supprimer
        $form = $this->get('form.factory')->create();
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->remove($advert);
            $em->flush();

        // Ici, on récupérera l'annonce correspondant à $id

        // Ici, on gérera la suppression de l'annonce en question

        $request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée.");
        return $this->redirectToRoute('oc_platform_home');
    }
         return $this->render('OCPlatformBundle:Advert:delete.html.twig', array(
          'advert' => $advert,
          'form'   => $form->createView(),
));
}
    public function menuAction($limit)
    {
        $em = $this->getDoctrine()->getManager();
        // On fixe en dur une liste ici, bien entendu par la suite
        // on la récupérera depuis la BDD !
       $listAdverts =
           /*array(
            array('id' => 2, 'title' => 'Recherche développeur Symfony'),
            array('id' => 5, 'title' => 'Mission de webmaster'),
            array('id' => 9, 'title' => 'Offre de stage webdesigner')
        );
*/
           $em->getRepository('OCPlatformBundle:Advert')->findBy(
               array(),                 // Pas de critère
               array('date' => 'desc'), // On trie par date décroissante
               $limit,                  // On sélectionne $limit annonces
               0                        // À partir du premier
           );

        return $this->render('OCPlatformBundle:Advert:menu.html.twig',
            array(
            // Tout l'intérêt est ici : le contrôleur passe
            // les variables nécessaires au template !
            'listAdverts' => $listAdverts
        ));
    }
    // Méthode facultative pour tester la purge
    public function purgeAction($days, Request $request)
    {
        // On récupère notre service
        $purger = $this->get('oc_platform.purger.advert');
        // On purge les annonces
        $purger->purge($days);
        // On ajoute un message flash arbitraire
        $request->getSession()->getFlashBag()->add('info', 'Les annonces plus vieilles que '.$days.' jours ont été purgées.');
        // On redirige vers la page d'accueil
        return $this->redirectToRoute('oc_platform_home');
    }
    /**
     * @Route("/admin")
     */
    public function adminAction()
    {
        return new Response('<html><body>Admin page!</body></html>');
    }

    public function translationAction($name)
    {
        return $this->render('OCPlatformBundle:Advert:translation.html.twig', array(
            'name' => $name
        ));
    }

    }
