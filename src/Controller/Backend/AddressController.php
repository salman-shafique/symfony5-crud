<?php

namespace App\Controller\Backend;

use App\Entity\Address;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntityValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/backend/addresses")
 */
class AddressController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
     * @Route("/{id}/edit", name="address_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Address $address): JsonResponse
    {
        $data = [];
        $data['street'] = $request->get('street');
        $data['zip'] = $request->get('zip');
        $data['city'] = $request->get('city');
        $data['country'] = $request->get('country');
        if (empty($data['street']) || empty($data['zip']) || empty($data['city']) || empty($data['country'])) {
            return new JsonResponse(['status' => 'Please fill all required fields.'], Response::HTTP_BAD_REQUEST);
        }
        $entityManager = $this->getDoctrine()->getManager();

        $address->setStreet($data['street']);
        $address->setZip($data['zip']);
        $address->setCity($data['city']);
        $address->setCountry($data['country']);
        $entityManager->persist($address);
        $entityManager->flush();
        return new JsonResponse(['status' => 'Address Updated!'], Response::HTTP_OK);

    }

    /**
     * @Route("/{id}/delete", name="address_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Address $address): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($address);
        $entityManager->flush();
        return new JsonResponse(['status' => 'Address Deleted!'], Response::HTTP_OK);
    }
}
