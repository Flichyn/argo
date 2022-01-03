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
            if ($request->request->get('member')['name'] === "klm") {
                $this->addFlash('danger', 'C\'est pas bon mdr');
            } elseif ($request->request->get('member')['name'] === "mlk") {
                $this->addFlash('info', 'C\'est pas bon mdr');
            } elseif ($request->request->get('member')['name'] != "klm" && $request->request->get('member')['name'] != "mlk") {
                $memberName = $request->request->get('member')['name'];
                $entityManager->persist($member);
                $entityManager->flush();

                $this->addFlash('success', 'Le membre ' . $memberName . ' a bien été ajouté dans l\'équipage !');

                return $this->redirectToRoute('home');
            }
        }

        return $this->render('home/index.html.twig', [
            'members' => $members,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id<^[0-9]+$>}", name="member_delete", methods={"POST"})
     * @param Request $request
     * @param Member $member
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(Request $request, Member $member, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $member->getId(), $request->request->get('_token'))) {
            $memberName = $member->getName();
            $entityManager->remove($member);
            $entityManager->flush();

            $this->addFlash('danger', 'Le membre ' . $memberName . ' a bien été supprimé.');
        }

        return $this->redirectToRoute('home');
    }
}
