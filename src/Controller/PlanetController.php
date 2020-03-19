<?php

namespace App\Controller;
use App\Repository\PlanetaRepository;
//use App\Entity\Planeta;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


    /**
     * Class PlanetController
     * @package App\Controller
     *
     * @Route(path="/api/")
     */
class PlanetController
{
    private $planetaRepository;

    public function __construct(PlanetaRepository $planetaRepository)
    {
        $this->planetaRepository = $planetaRepository;
    }

    
    /**
     * @Route("planet", name="add_planeta", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'];
        $description = $data['description'];
        $position = $data['position'];

        $planet= $this->planetaRepository->findOneBy(['name' => $name]);

        if($planet->getName() == $name){
            return new JsonResponse(['msg' => 'Este planeta ya existe. Ingrese otro nombre'], Response::HTTP_OK);
        }
        
        if (empty($name) || empty($description) || empty($position)) {
           // throw new NotFoundHttpException('Expecting mandatory parameters!');
           return new JsonResponse(['msg' => 'Ninguno de los campos puede estar vacío'], Response::HTTP_OK);
        }

        $this->planetaRepository->savePlanet($name, $description, $position);

        return new JsonResponse(['status' => 'Planet created!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("planet/{id}", name="get_one_planet", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $planet= $this->planetaRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $planet->getId(),
            'name' => $planet->getName(),
            'description' => $planet->getDescription(),
            'position' => $planet->getPosition(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

     /**
     * @Route("planets", name="get_all_planets", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $planetas = $this->planetaRepository->findAll();
        $data = [];

        foreach ($planetas as $planet) {
            $data[] = [
                'id' => $planet->getId(),
                'name' => $planet->getName(),
                'description' => $planet->getDescription(),
                'position' => $planet->getPosition(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

     /**
     * @Route("planet/{id}", name="update_planet", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $planet = $this->planetaRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['name']) ? true : $planet->setName($data['name']);
        empty($data['description']) ? true : $planet->setDescription($data['description']);
        empty($data['position']) ? true : $planet->setPosition($data['position']);
        empty($data['position']) ? true : $planet->setPosition($data['position']);
        $planet->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
        $updatedPlanet = $this->planetaRepository->updatePlanet($planet);

		return new JsonResponse(['status' => 'Planet updated!'], Response::HTTP_OK);
    }

    /**
     * @Route("planet/{id}", name="delete_planet", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $planet = $this->planetaRepository->findOneBy(['id' => $id]);

        $this->planetaRepository->removePlanet($planet);

        return new JsonResponse(['status' => 'Planet deleted'], Response::HTTP_OK);
    }
}
