<?php

namespace App\Controller;

use App\Data\SearchUser;
use App\Entity\Groupe;
use App\Entity\User;
use App\Form\SearchType;
use App\Repository\GroupeRepository;
use App\Repository\UserRepository;
use App\Service\Functions;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    public function __construct()
    {
    }

    /**
     * @Route("/", name="home")
     */
    public function index(UserRepository $userRepository, GroupeRepository $groupeRepository, EntityManagerInterface $em, Request $request): Response
    {
        $data = new SearchUser();
        $groupe = $this->getDoctrine()->getRepository(Groupe::class)->find(11);
        $data->groupe[] = $groupe;
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);


        $users = $userRepository->findSearchUser($data);

        /* foreach($users as $user){
             $user->setGroupe($groupeRepository->find(rand(10,14)));
             $em->flush();
         }*/

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'users' => $users,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param User $user
     * @param int $points
     * @param EntityManagerInterface $em
     * @Route("/gestionPoints/{id}/{quantity}/{operation}/", name="gestionpoint")
     */
    public function attribuerPoints(User $user, int $points = null, $operation, $quantity, EntityManagerInterface $em)
    {

        $pointsUser = $user->getTotalPoints();

        if ($quantity > 0 and $operation == 'ajout') {
            $user->setTotalPoints($pointsUser + intval($quantity));
        } else {
            $user->setTotalPoints($user->getTotalPoints() - intval($quantity));
        }
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('home');

    }


}
