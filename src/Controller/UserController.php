<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\WebserviceUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;


class UserController extends Controller
{
    /**
     * @Route("/users/{username}")
     */
    public function getByUsername($username)
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);


        $repository = $this->getDoctrine()->getRepository(WebserviceUser::class);
        $user = $repository->findOneBy([
            'username' => $username
        ]);
        $jsonContent = $serializer->serialize($user, 'json');
        return new Response($jsonContent);
    }

    /**
     * @Route("/users/apikey/{apiKey}", name="get_user_by_apikey")
     */
    public function getByApiKey($apiKey)
    {
        $res = array();

        $repository = $this->getDoctrine()->getRepository(WebserviceUser::class);
        $user = $repository->findOneBy([
            'apiKey' => $apiKey
        ]);
        $res["username"] = $user->getUsername();
        return new JsonResponse($res);
    }    
}