<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
    * @Route("/register", name="user_regiset", methods={"POST"})
    */

    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $entityManager  = $this->getDoctrine()->getManager();
        $data           = json_decode($request->getContent(), true);
        $firstname      = $data['firstname'];
        $lastname       = $data['lastname'];
        $phone_number   = $data['phone_numer'];
        $email          = $data['email'];
        $plainPassword  = $data['password'];

        if (empty($firstname) || empty($lastname) || empty($phone_number) || empty($email) || empty($password)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $constraint = new Assert\Collection(array(
            'email' => new Assert\Email(),
            'password' => new Assert\NotNull(),
        ));

        $violations = $validator->validate($data, $constraint);
        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], 500);
        }

        $user = new User();
        $plainPassword = $data['password'];
        $encoded = $encoder->encodePassword($user, $plainPassword);

        $user
            ->setFistname($data['firstname'])
            ->setLastName($data['lastname'])
            ->setPhoneNumber($data['phone_number'])
            ->setEmail($data['email'])
            ->setPassword($encoded);
        try {
            $entityManager->persist($user);
            $entityManager->flush();
        } catch (Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], 500);
        }

        $answer = json_encode($user->toArray());

        return new Response($answer, Response::HTTP_CREATED);
    }

}
