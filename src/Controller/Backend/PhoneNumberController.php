<?php

namespace App\Controller\Backend;

use App\Entity\PhoneNumber;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/backend/phone_numbers")
 */
class PhoneNumberController extends AbstractController
{

    /**
     * @Route("/{id}/edit", name="phone_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PhoneNumber $phoneNumber): JsonResponse
    {
        $data = [];
        $data['phone_no'] = $request->get('phone_no');
        if (empty($data['phone_no'])) {
            return new JsonResponse(['status' => 'Please fill all required fields.'], Response::HTTP_BAD_REQUEST);
        }
        $entityManager = $this->getDoctrine()->getManager();

        $phoneNumber->setPhoneNo($data['phone_no']);
        $entityManager->persist($phoneNumber);
        $entityManager->flush();
        return new JsonResponse(['status' => 'Phone number Updated!'], Response::HTTP_OK);

    }

    /**
     * @Route("/{id}/delete", name="phone_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PhoneNumber $phoneNumber): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($phoneNumber);
        $entityManager->flush();
        return new JsonResponse(['status' => 'Phone number Deleted!'], Response::HTTP_OK);
    }
}
