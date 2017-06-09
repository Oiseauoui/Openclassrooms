<?php
// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\HttpFoundation\RedirectResponse; // N'oubliez pas ce use
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class AdvertController extends Controller
{
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
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        // Ici, on récupérera la liste des annonces, puis on la passera au template

 //https://openclassrooms.com/courses/developpez-votre-site-web-avec-le-framework-symfony/les-services-theorie-et-creation-1
        // On a donc accès au conteneur :

        $mailer = $this->container->get('mailer');

        // On peut envoyer des e-mails, etc.

        //https://openclassrooms.com/courses/developpez-votre-site-web-avec-le-framework-symfony/le-moteur-de-templates-twig-1
        // Mais pour l'instant, on ne fait qu'appeler le template
        // Dans l'action indexAction() :

        // Notre liste d'annonce en dur
        $listAdverts = array(
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


        );

        // Et modifiez le 2nd argument pour injecter notre liste
        return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
            'listAdverts' => $listAdverts
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

            /*
             *
             *
            // On crée la réponse sans lui donner de contenu pour le moment
            $response = new Response();

            // On définit le contenu
            $response->setContent("Ceci est une page d'erreur 404");

            // On définit le code HTTP à « Not Found » (erreur 404)
            $response->setStatusCode(Response::HTTP_NOT_FOUND);

            // On retourne la réponse
            return $response;
             }*/

        /*    return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
                'id' => $id
            ));
        }
*/
            // Ici, on récupérera l'annonce correspondante à l'id $id

            $advert = array(
                'title'   => 'Recherche développpeur Symfony2',
                'id'      => $id,
                'author'  => 'Alexandre',
                'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
                'date'    => new \Datetime()
            );

            return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
                'advert' => $advert
            ));

        }



    // Ajoutez cette méthode :
   public function addAction(Request $request)
    {
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
       if ($request->isMethod('POST')) {
           // Ici, on s'occupera de la création et de la gestion du formulaire

           $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

           // Puis on redirige vers la page de visualisation de cettte annonce
           return $this->redirectToRoute('oc_platform_view', array('id' => 5));
       }

       // Si on n'est pas en POST, alors on affiche le formulaire
       return $this->render('OCPlatformBundle:Advert:add.html.twig');


       //https://openclassrooms.com/courses/developpez-votre-site-web-avec-le-framework-symfony/les-services-theorie-et-creation-1
       // On récupère le service
       $antispam = $this->container->get('oc_platform.antispam');

       // Je pars du principe que $text contient le texte d'un message quelconque
       $text = '...';
       if ($antispam->isSpam($text)) {
           throw new \Exception('Votre message a été détecté comme spam !');
       }

       // Ici le message n'est pas un spam
   }


    public function editAction($id, Request $request)
    {
        // Ici, on récupérera l'annonce correspondante à $id

        // Même mécanisme que pour l'ajout
        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

            return $this->redirectToRoute('oc_platform_view', array('id' => 5));
        }

        $advert = array(
            'title' => 'Recherche développpeur Symfony',
            'id' => $id,
            'author' => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
            'date' => new \Datetime()
        );

        return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
            'advert' => $advert
        ));
    }

    public function deleteAction($id)
    {
        // Ici, on récupérera l'annonce correspondant à $id

        // Ici, on gérera la suppression de l'annonce en question

        return $this->render('OCPlatformBundle:Advert:delete.html.twig');
    }
    public function menuAction($limit)
    {
        // On fixe en dur une liste ici, bien entendu par la suite
        // on la récupérera depuis la BDD !
        $listAdverts = array(
            array('id' => 2, 'title' => 'Recherche développeur Symfony'),
            array('id' => 5, 'title' => 'Mission de webmaster'),
            array('id' => 9, 'title' => 'Offre de stage webdesigner')
        );

        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
            // Tout l'intérêt est ici : le contrôleur passe
            // les variables nécessaires au template !
            'listAdverts' => $listAdverts
        ));
    }


    }