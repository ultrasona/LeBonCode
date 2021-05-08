<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Advert;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AdvertRepository;



class AdvertController extends AbstractController
{
    private $advertRepository;

    public function __construct(AdvertRepository $advertRepository)
    {
        $this->advertRepository = $advertRepository;
    }
    /**
     * @Route("/advert", name="create_advert", methods={"POST"})
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
        $answer = json_encode($advert->toArray());

        return new Response($answer, Response::HTTP_CREATED);
    }

    /**
     * @Route("/advert/{id}", name="get_advert", methods={"GET"})
     */
    public function get($id): Response
    {
        $advert = $this->advertRepository->findOneBy(['id' => $id]);
        $answer   = json_encode($advert->toArray());
        return new Response($answer, Response::HTTP_OK);
    }

    /**
    * @Route("/advert/{id}", name="update_advert", methods={"PATCH"})
    */
    public function update($id, Request $request) : Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $advert = $this->advertRepository->findOneBy(['id' => $id]);
        $data   = json_decode($request->getContent(), true);

        empty($data['title']) ? true : $advert->setTitle($data['title']);
        empty($data['description']) ? true : $advert->setDescription($data['description']);
        empty($data['price']) ? true : $advert->setPrice($data['price']);
        empty($data['postal_code']) ? true : $advert->setPostalCode($data['postal_code']);
        empty($data['city']) ? true : $advert->setCity($data['city']);

        $entityManager->persist($advert);
        $entityManager->flush();
        $answer         = json_encode($advert->toArray());

        return new Response($answer, Response::HTTP_OK);
    }
}
