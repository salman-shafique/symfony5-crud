<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/users")
 */
class UserController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(Request $request, UserRepository $userRepository): Response
    {

        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findByKeyWord($request->get('keyword')),
            'keyword' => $request->get('keyword'),
        ]);
    }

    /**
     * @Route("/create", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        if ($request->getMethod() == "POST") {
            $data = [];
            $data['first_name'] = $request->get('first_name');
            $data['last_name'] = $request->get('last_name');
            $data['email'] = $request->get('email');
            $data['password'] = $request->get('password');
            $data['dob'] = $request->get('dob');
            $data['address'] = $request->get('address');
            $data['phone'] = $request->get('phone');
            $data['roles'] = $request->get('roles');
            if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email']) || empty($data['dob']) || empty($data['password']) || empty($data['roles']) || count($data['phone']) == 0 || count($data['address']) == 0) {
                $this->addFlash('error', 'Please fill all required fields.');
                return $this->redirectToRoute('user_new');
            }
            $this->userRepository->store($data);
            $this->addFlash('success', 'User created successfully');
            return $this->redirectToRoute('user_index');

        }
        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        $addresses = $user->getAddresses();
        $phoneNumbers = $user->getPhoneNumbers();
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'addresses' => $addresses,
            'phone_numbers' => $phoneNumbers,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($request->getMethod() == "POST") {
            $data = [];
            $data['first_name'] = $request->get('first_name');
            $data['last_name'] = $request->get('last_name');
            $data['email'] = $request->get('email');
            $data['password'] = $request->get('password');
            $data['dob'] = $request->get('dob');
            $data['address'] = $request->get('address');
            $data['phone'] = $request->get('phone');
            $data['roles'] = $request->get('roles');
            if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email']) || empty($data['dob'])) {
                $this->addFlash('error', 'Please fill all required fields.');
                return $this->redirectToRoute('user_new');
            }
            $this->userRepository->update($user, $data);
            $this->addFlash('success', 'User updated successfully');
            return $this->redirectToRoute('user_index');

        }
        $addresses = $user->getAddresses();
        $phoneNumbers = $user->getPhoneNumbers();
        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'addresses' => $addresses,
            'phone_numbers' => $phoneNumbers,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        return new JsonResponse(['status' => 'User Deleted!'], Response::HTTP_OK);
    }
}
