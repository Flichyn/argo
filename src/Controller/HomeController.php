<?php

namespace App\Controller;

use App\Entity\Member;
use App\Form\MemberType;
use App\Repository\MemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, MemberRepository $memberRepository): Response
    {
        $members = $memberRepository->findAll();

        $member = new Member();

        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
//            dd($request->request->get('member')['name']);
            if ($request->request->get('member')['name'] === "klm") {
                $this->addFlash('danger', 'C\'est pas bon mdr');
            } elseif ($request->request->get('member')['name'] === "mlk") {
                $this->addFlash('info', 'C\'est pas bon mdr');
            } elseif ($request->request->get('member')['name'] != "klm" && $request->request->get('member')['name'] != "mlk") {
                $entityManager->persist($member);
                $entityManager->flush();

                $this->addFlash('success', 'Le membre a bien été ajouté dans l\'équipage !');
            }
        }

        return $this->render('home/index.html.twig', [
            'members' => $members,
            'form' => $form->createView()
        ]);
    }
}
