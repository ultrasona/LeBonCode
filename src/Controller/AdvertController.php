<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Advert;
use Doctrine\ORM\EntityManagerInterface;

class AdvertController extends AbstractController
{
    /**
     * @Route("/advert", name="create_avert", methods={"POST"})
     */
    public function createAdvert(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $data           = json_decode($request->getContent(), true);
        $title          = $data['title'];
        $description    = $data['description'];
        $price          = $data['price'];
        $postal_code    = $data['postal_code'];
        $city           = $data['city'];

        if (empty($title) || empty($description) || empty($price) || empty($postal_code) || empty($city)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }
       
        $advert = new Advert();
        $advert ->setTitle($title)
                ->setDescription($description)
                ->setPrice($price)
                ->setPostalCode($postal_code)
                ->setCity($city);

        $entityManager->persist($advert);
        $entityManager->flush();
        $json = json_encode($advert->toArray());

        return new Response($json, Response::HTTP_CREATED);
    }

    /**
     * @Route("/advert/{id}", name="create_avert", methods={"DELETE"})
     */
}
