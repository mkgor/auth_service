<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserParameter;
use App\Service\EncoderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class MainController
 *
 * @package App\Controller
 */
class MainController extends \FOS\RestBundle\Controller\AbstractFOSRestController
{
    /**
     * @var EntityManagerInterface
     */
    private $doctrine;

    /**
     * @var EncoderService
     */
    private $encoder;

    /**
     * MainController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param EncoderService         $encoder
     */
    public function __construct(EntityManagerInterface $entityManager, EncoderService $encoder)
    {
        $this->doctrine = $entityManager;
        $this->encoder = $encoder;
    }

    /**
     * @Rest\Get("/")
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->json([
            'status' => 'Welcome to authentication service!',
        ]);
    }

    /**
     * @Rest\Post("/user/login")
     *
     * @param Request $request
     *
     * @return object|JsonResponse
     * @throws \Exception
     */
    public function login(Request $request)
    {
        $userRepository = $this->doctrine->getRepository(User::class);

        $user = $userRepository->findOneBy(['login' => $request->get('login')]);

        if ($user) {
            if ($this->encoder->checkPassword($request->get('password'), $user->getPasswordSalt(), $user->getPasswordHash())) {
                $token = $this->encoder->generateRandomHash();

                $user->setAuthToken($token);

                $this->doctrine->persist($user);
                $this->doctrine->flush();

                return $this->json([
                    'user_id' => $user->getId(),
                    'token'   => $token,
                ]);
            }
        }

        return $this->json([
            'message' => 'Invalid credentials or user does not exist',
        ])->setStatusCode(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\Post("/user/register")
     *
     * @param Request $request
     *
     * @return object|JsonResponse
     * @throws \Exception
     */
    public function register(Request $request)
    {
        $validator = Validation::createValidator();

        $violations = $validator->validate([
            'login'    => $request->get('login'),
            'password' => $request->get('password'),
        ], [
            'login'    => new Assert\All([
                new Assert\Type('string'),
                new Assert\Required(),
                new Assert\NotBlank(),
            ]),
            'password' => new Assert\All([
                new Assert\Type('string'),
                new Assert\Required(),
                new Assert\NotBlank(),
            ]),
        ]);

        if (count($violations) > 0) {
            /** @var ConstraintViolation $violation */
            $errorsList = [];

            foreach ($violations as $violation) {
                $errorsList[trim($violation->getPropertyPath(),'[]')] = $violation->getMessage();
            }

            return $this->json([
                'errors' => $errorsList,
            ])->setStatusCode(400);
        }

        $passwordHash = $this->encoder->encode($request->get('password'));

        $user = new User();

        $user->setLogin($request->get('login'));
        $user->setPasswordHash($passwordHash['hash']);
        $user->setPasswordSalt($passwordHash['salt']);

        $this->doctrine->persist($user);
        $this->doctrine->flush();

        if($request->request->has('params')) {
            foreach($request->get('params') as $key => $value) {
                $userParameter = new UserParameter();

                $userParameter->setUser($user);
                $userParameter->setParamKey($key);
                $userParameter->setParamValue(is_array($value) || is_object($value) ? json_encode($value) : $value);

                $this->doctrine->persist($userParameter);
            }

            $this->doctrine->flush();
        }

        return $this->json([
            'user_id' => $user->getId()
        ]);
    }

    /**
     * @Rest\Get("/user/verify")
     * @param Request $request
     *
     * @return JsonResponse|Response
     */
    public function verifyToken(Request $request)
    {
        $userRepository = $this->doctrine->getRepository(User::class);

        $user = $userRepository->findOneBy(['auth_token' => $request->get('token')]);

        if($user) {
            return $this->handleView($this->view([
                'id' => $user->getId(),
                'login' => $user->getLogin(),
                'parameters' => $user->getParameters(),
                'verification' => true
            ], Response::HTTP_OK));
        }

        return $this->json(['verification' => false]);
    }
}
