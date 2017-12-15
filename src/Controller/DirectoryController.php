<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\WebserviceUser;
use App\Entity\Directory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;


class DirectoryController extends Controller
{
    /**
     * @Route("/api/directories/{parent}")
     */
    public function getDirectories(Request $request, $parent)
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $encoders = array('json' => new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $apiKey = $request->query->get('apikey');
        if($apiKey == null ){
            $apiKey = $request->headers->get('apikey');            
        }

        $doctrine = $this->getDoctrine();
        $user = $doctrine->getRepository(WebserviceUser::class)->findOneBy([
            'apiKey' => $apiKey
        ]);

        $parent = $doctrine->getRepository(Directory::class)->findOneBy([
            'id' => $parent
        ]);

        $directories = $doctrine->getRepository(Directory::class)->findBy([
            'user' => $user,
            'parent' => $parent
        ]);

        $data = $serializer->normalize($directories, null, array('attributes' => array('id', 'name',  'parent' => ['id'])));
        $jsonContent = $serializer->serialize($data, 'json');
        return new Response($jsonContent);
    }

    /**
     * @Route("/api/directories", methods="POST")
     */
    public function createDirectory(Request $request)
    {
        $json = json_decode($request->getContent(), true);        
        $apiKey = $request->query->get('apikey');
        if($apiKey == null ){
            $apiKey = $request->headers->get('apikey');            
        }
        $doctrine = $this->getDoctrine();
 
        $user = $doctrine->getRepository(WebserviceUser::class)->findOneBy([
            'apiKey' => $apiKey
        ]);
        $parent = $doctrine->getRepository(Directory::class)->find($json["parent"]);
        $directory = new Directory($json["name"], $parent, $user);

        $doctrine->getManager()->persist($directory);
        $doctrine->getManager()->flush();
    }


    /**
     * @Route("/api/directories/delete/{id}", methods="DELETE")
     */
    public function DeleteDirectory(Request $request, $id)
    {
        $doctrine = $this->getDoctrine();
        $directory = $doctrine->getRepository(Directory::class)->find($id);
        dump($directory->getChildren()->getValues());
        $doctrine->getManager()->remove($directory);
        $doctrine->getManager()->flush();
    }

}