<?php

namespace App\Controller;

use App\Entity\Games;
use App\Entity\Users;
use App\Form\GamesType;
use App\Form\UsersType;
use App\Entity\Bookings;
use App\Entity\Formulas;
use App\Form\FormulasType;
use App\Form\AdminBookingsType;
use App\Repository\GamesRepository;
use App\Repository\UsersRepository;
use App\Repository\BookingsRepository;
use App\Repository\FormulasRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    //________________________USERS RELATED_____________________________________________________________________________
    //__________________________________________________________________________________________________________________

    /**
     * @Route("/users", name="admin_users_index", methods={"GET"})
     */
    public function indexUsers(UsersRepository $usersRepository): Response
    {
        return $this->render('users/index.html.twig', [
            'users' => $usersRepository->findAll(),
        ]);
    }

    /**
     * @Route("/users/new", name="admin_users_new", methods={"GET","POST"})
     */
    public function newUser(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $encoded = $encoder->encodePassword($user, $user->getPassword()); //crypter le password
            $user->setPassword($encoded); //password crypté à mettre dans la table
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render('users/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    //________________________GAMES RELATED_____________________________________________________________________________
    //__________________________________________________________________________________________________________________

    /**
     * @Route("/games", name="admin_games_index", methods={"GET"})
     */
    public function indexGames(GamesRepository $gamesRepository): Response
    {
        return $this->render('games/index.html.twig', [
            'games' => $gamesRepository->findAll(),
        ]);
    }

     /**
     * @Route("/games/new", name="admin_games_new", methods={"GET","POST"})
     */
    public function newGame(Request $request): Response
    {
        $game = new Games();
        $form = $this->createForm(GamesType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($game);
            $entityManager->flush();

            return $this->redirectToRoute('admin_games_index', [
                'id' => $game->getId(),
            ]);
        }

        return $this->render('games/new.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/games/{id}/edit", name="admin_games_edit", methods={"GET","POST"})
     */
    public function editGame(Request $request, Games $game): Response
    {
        $form = $this->createForm(GamesType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('games_index', [
                'id' => $game->getId(),
            ]);
        }

        return $this->render('games/edit.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/games/{id}", name="admin_games_delete", methods={"DELETE"})
     */
    public function deleteGame(Request $request, Games $game): Response
    {
        if ($this->isCsrfTokenValid('delete'.$game->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($game);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_games_index');
    }

        /**
     * @Route("/games/{id}", name="admin_games_show", methods={"GET"})
     */
    public function show(Games $game): Response
    {
        return $this->render('games/show.html.twig', [
            'game' => $game,
        ]);
    }

    //________________________FORMULAS RELATED_____________________________________________________________________________
    //_____________________________________________________________________________________________________________________

    /**
     * @Route("/formulas", name="admin_formulas_index", methods={"GET"})
     */
    public function indexFormulas(FormulasRepository $formulasRepository): Response
    {
        return $this->render('formulas/admin/index.html.twig', [
            'formulas' => $formulasRepository->findAll(),
        ]);
    }
     /**
     * @Route("/formulas/admin/new", name="admin_formulas_new", methods={"GET","POST"})
     */
    public function newFormula(Request $request): Response
    {
        $formula = new Formulas();
        $form = $this->createForm(FormulasType::class, $formula);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formula);
            $entityManager->flush();

            return $this->redirectToRoute('admin_formulas_index');
        }

        return $this->render('/formulas/admin/new.html.twig', [
            'formula' => $formula,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/formulas/admin/{id}/edit", name="admin_formulas_edit", methods={"GET","POST"})
     */
    public function editFormula(Request $request, Formulas $formula): Response
    {
        $form = $this->createForm(FormulasType::class, $formula);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_formulas_index', [
                'id' => $formula->getId(),
            ]);
        }

        return $this->render('/formulas/admin/edit.html.twig', [
            'formula' => $formula,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/formulas/admin/{id}", name="admin_formulas_delete", methods={"DELETE"})
     */
    public function deleteFormula(Request $request, Formulas $formula): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formula->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($formula);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_formulas_index');
    }

       /**
     * @Route("/formulas/admin/{id}", name="aformulas_show", methods={"GET"})
     */
    public function ashowFormula(Formulas $formula): Response
    {
        return $this->render('formulas/admin/show.html.twig', [
            'formula' => $formula,
        ]);
    }

    //________________________BOOKINGS RELATED_____________________________________________________________________________
    //_____________________________________________________________________________________________________________________

    /**
     * @Route("/bookings/index", name="admin_bookings_index", methods={"GET"})
     */
    public function indexBookings(BookingsRepository $bookingsRepository): Response
    {
        return $this->render('bookings/index.html.twig', [
            'bookings' => $bookingsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_bookings_edit", methods={"GET","POST"})
     */
    public function editBooking(Request $request, Bookings $booking): Response
    {
        $form = $this->createForm(AdminBookingsType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_bookings_index', [
                'id' => $booking->getId(),
            ]);
        }

        return $this->render('bookings/status_form.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    }
}