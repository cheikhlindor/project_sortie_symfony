<?php


namespace App\Controller\Api;


use App\data\SearcheData;
use App\Entity\Sortie;
use App\Repository\SortieRepository;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * @Route("/api")
 */

class ApiSortieController extends AbstractController
{
    /**
     * Liste des sorties
     * @Route("/sortie", name="api_sortie_liste", methods={"GET"})
     */
    public function liste(SortieRepository $repository, SerializerInterface $serializer)
    {
        $data= new SearcheData();
        $sorties=$repository->findRecentSortie($data);

        $json = $serializer->serialize(
               $sorties,
               'json', ['groups' => 'liste_sorties'] );


        //, Request $request=>get content (request)-> desialize (la classe à récupérer)

        return new Response($json, Response::HTTP_OK, ["Content-type"=>"application/json"]);

    }

    /**
     * @Route("/sortie/{id}", name="api_sortie_detail",  requirements={"id": "\d+"}, methods={"GET"})
     * @param $id
     * @param SerializerInterface $serializer
     * @return JsonResponse|Response
     */
    public function detailSortie($id, SerializerInterface $serializer)
    {
        $sortieRepo=$this->getDoctrine()->getRepository(Sortie::class);
        $sortie=$sortieRepo->find($id);
        $json = $serializer->serialize($sortie, 'json');
        if (empty($sortie)) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        return new Response($json, Response::HTTP_OK, ["Content-type"=>"application/json"]);
    }

}