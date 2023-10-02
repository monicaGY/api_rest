<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Producto;
use App\Entity\Seccion;
use Symfony\Component\HttpFoundation\Request;


class ClientController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }
    #[Route('/api/doctrine/client/products')]
    public function list_products(): JsonResponse
    {
        $products = $this->em->getRepository(Producto::class)->findAll();


        $array_products = [];
        foreach ($products as $key => $value) {
            $array_products[] =[
                'id' => $value->getId(),
                'nombre' => $value->getNombre(),
                'precio' => $value->getPrecio(),
                'seccion' =>$value->getSeccion()->getNombre()
                    
            ]; 
        }
    
        if(empty($array_products)){
            return $this->json([
                'estado'=>'error',
                'mensaje'=>'no se han creado productos'
            ]);
        }
        return $this->json($array_products);
    }


    #[Route('/api/doctrine/client/sections')]
    public function list_sections(): JsonResponse
    {
        $sections = $this->em->getRepository(Seccion::class)->findAll();


        $sections_array = [];
        foreach ($sections as $key => $value) {
            $sections_array[] =[
                'id' => $value->getId(),
                'nombre' => $value->getNombre()
                    
            ]; 
        }
        
        if(empty($sections_array)){
            return $this->json([
                'estado'=>'error',
                'mensaje'=>'no se han creado secciones'
            ]);
        }
        return $this->json($sections_array);
    }


    #[Route('/api/doctrine/client/products/section/{id}')]
    public function products_by_section(int $id): JsonResponse
    {
        $section = $this->em->getRepository(Seccion::Class)->find($id);

        if(empty($section)){
            return $this->json([
                'estado'=>'error',
                'mensaje'=>'no existen la sección'
            ]);
        }
        $products = $this->em->getRepository(Producto::class)->findBy(array('seccion'=> $id),array('id'=>'ASC'));
        
        
        if(empty($products)){
            return $this->json([
                'estado'=>'error',
                'mensaje'=>'no existen productos en esta sección'
            ]);
        }

        $products_array = [];
        foreach ($products as $key => $value) {
            $products_array[] =[
                'id' => $value->getId(),
                'nombre' => $value->getNombre(),
                'precio' => $value->getPrecio(),
                    
            ]; 
        }

       

        return $this->json([
            'seccion'=>$products[0]->getSeccion()->getNombre(),
            'productos' => $products_array
        ]);
    }

    #[Route('/api/doctrine/client/products/update/{id}')]
    public function product_update(int $id, Request $request): JsonResponse{

        $product = $this->em->getRepository(Producto::class)->findById($id);
        $data_product = json_decode($request->getContent(),true);
        
        if(empty($product)){
            return $this->json([
                'estado'=>'error',
                'mensaje'=>'No se ha encontrado el producto'
            ]);
        }

        if( empty($data_product['seccion_id']) ||
            empty($data_product['nombre']) ||
            empty($data_product['precio'])
        ){
            return $this->json([
                'estado'=>'error',
                'mensaje'=>'Faltan parámetros'
            ]);
        }

        $section = $this->em->getRepository(Seccion::class)->find($data_product['seccion_id']);
        if(empty($section)){
            return $this->json([
                'estado'=>'error',
                'mensaje'=>'La categoría no existe'
            ]);
        }

        if(!is_numeric($data_product['precio'])){
            return $this->json([
                'estado'=>'error',
                'mensaje'=>'El precio debe ser numérico'
            ]);
        }

        $producto = new Producto();
        $producto->setNombre($data_product['nombre']);
        $producto->setSeccion($section);
        $producto->setPrecio($data_product['precio']);
        $this->em->flush();
        return $this->json([
            'estado'=>'ok',
            'mensaje'=>'Producto modificado con éxito'
        ]);
    }
}
