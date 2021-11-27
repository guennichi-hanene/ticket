<?php

namespace App\Controller;

use App\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
    #[Route('/ticket', name: 'ticket')]
    public function index(): Response
    {
        return $this->render('ticket/index.html.twig', [
            'controller_name' => 'TicketController',
        ]);
    }
    //ajout ticket/// 
    #[Route('/add/{titre}/{personne}/{statut}/{description}/{date}', name: 'ticket.add')]
    public function addTicket($titre, $statut, $description, $date)
    {
        $manager = $this->getDoctrine()
            ->getManager();
        $ticket = new Ticket();
        $ticket->setTitre($titre);

        $ticket->setStatut("en attente");
        $ticket->setDescription($description);
        $ticket->setDate($date);


        //Persister ajouter dans la transaction
        $manager->persist($ticket);
        //       
        //executer la transaction
        $manager->flush();
        return $this->render('ticket/ticket-details.html.twig', [
            'ticket' => $ticket
        ]);
    }
    /////suppression d'un ticket///////
    #[Route('/delete/{id}', name: 'ticket.delete')]
    public function deleteTicket(Ticket $ticket = null)
    {
        if ($ticket) {
            // recupérer manager
            $manager = $this->getDoctrine()->getManager();
            // supprime le user avec le id
            $manager->remove($ticket);
            $manager->flush();
            $this->addFlash('success', "le ticket a été supprimé avec succès");
        } else {
            $this->addFlash('error', "Erreur veuillez vérifier votre requete");
        }
        return $this->forward('App\Controller\TicketController::listAllTickets');
    }
    #[Route('/details/{id}', name: 'ticket.details')]
    public function getTicketById(Ticket $ticket = null)
    {
        if (!$ticket) {
            $this->addFlash('error', "Erreur veuillez vérifier votre requete");
        }
        return $this->render('ticket/ticket-details.html.twig', [
            'ticket' => $ticket
        ]);
    }
    ////lister tous les tickets///
    #[Route('/all', name: 'ticket.list')]
    public function listAllTickets()
    {
        // Récupérer la liste des tickets
        $repository = $this->getDoctrine()->getRepository(Ticket::class);

        $ticket = $repository->findAll();
        // l'envoyer à twig
        return $this->render('personne/list.html.twig', [
            'ticket' => $ticket
        ]);
    }
    // lister les tickets par   /////
    #[Route('/all/{numPage?1}', name: 'ticket.list')]
    public function listTickets($numPage)
    {
        // Récupérer la liste des tickets
        $repository = $this->getDoctrine()->getRepository(Ticket::class);
        $limit = 9;
        $offset = ($numPage - 1) * $limit;
        $ticket = $repository->findBy([], [], $limit, $offset);
        // l'envoyer à twig
        return $this->render('personne/list.html.twig', [
            'ticket' => $ticket
        ]);
    }
}
