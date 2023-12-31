<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Producto;
use App\Entity\Seccion;

class AdminController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    #[Route('/api/doctrine/v1/admin/product')]
    public function create_product(Request $request): JsonResponse
    {

        $data_product =  json_decode($request->getContent(),true);

        if( empty($data_product['seccion_id']) || 
            empty($data_product['nombre']) ||
            empty($data_product['precio']))
        {
            return $this->json([
                'estado'=>'error',
                'mensaje'=>'Faltan parámetros para poder crear el producto'
            ], 400);
        }

        $section = $this->em->getRepository(Seccion::class)->find($data_product['seccion_id']);

        
        if(empty($section)){
            return $this->json([
                'estado'=>'error',
                'mensaje'=>'El id de la categoría no existe'
            ], 404);
        }

        if(!is_numeric($data_product['precio'])){
            return $this->json([
                'estado'=>'error',
                'mensaje'=>'El precio debe ser numérico'
            ], 400);
        }


        $producto = new Producto();
        $producto->setNombre($data_product['nombre']);
        $producto->setSeccion($section);
        $producto->setPrecio($data_product['precio']);


        $this->em->persist($producto);
        $this->em->flush();
        return $this->json([
            'estado'=>'ok',
            'mensaje'=>'Producto creado con éxito'
        ], 201);
    }

    #[Route('/api/doctrine/v1/admin/section')]
    public function create_section(Request $request): JsonResponse
    {

        $data_section = json_decode($request->getContent(), true);

        if(empty($data_section['nombre'])){
            return $this->json([
                "estado"=>"error",
                "mensaje"=>"Te falta el parámetro nombre"
            ], 400);
        }

        $section = $this->em->getRepository(Seccion::class)->findOneBy(array('nombre'=>$data_section['nombre']));


        if(!empty($section)){
            return $this->json([
                "estado"=>"error",
                "mensaje"=>"Ya existe una sección con ese nombre"
            ], 400);
        }

        
        $section = new Seccion();
        $section->setNombre($data_section['nombre']);
        $this->em->persist($section);
        $this->em->flush();
        return $this->json([
            "estado"=>"ok",
            "mensaje"=>"La seccion se ha creado"
        ], 201);
    }
    
    #[Route('/api/doctrine/v1/admin/product/{id}')]
    public function delete_product(Request $request, int $id): JsonResponse
    {
        $product = $this->em->getRepository(Producto::class)->find($id);
        
        if(empty($product)){
            return $this->json([
                "estado"=>"error",
                "mensaje"=>"No se ha encontrado el producto"
            ], 404);
        }

        $this->em->remove($product);
        $this->em->flush();
        return $this->json([
            "estado"=>"ok",
            "mensaje"=>"Se ha borrado con éxito el producto"
        ], 200);
    }
}
