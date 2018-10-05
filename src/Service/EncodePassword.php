<?php
/**
 * Created by PhpStorm.
 * User: stoyan.kalinov
 * Date: 3.10.2018 г.
 * Time: 15:50
 */

namespace App\Service;


use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\ServiceInterface\EncodePasswordInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EncodePassword implements EncodePasswordInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * EncodePasswords constructor.
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param RouterInterface $router
     */
    public function __construct(UserRepository $userRepository, $entityManager, UserPasswordEncoderInterface $passwordEncoder, RouterInterface $router)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->router = $router;
    }

    /**
     * @return User[]|object[]
     */
    public function encodeAllPasswords()
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();
        foreach ($users as $user)
        {
            $password = $user->getPassword();
            $newPassword = $this->passwordEncoder->encodePassword($user, $password);

            $user->setPassword($newPassword);
        }

        return $users;
    }

    public function setEncodedPasswords(): void
    {
        $usersWithEncodedPasswords = $this->encodeAllPasswords();

        foreach ($usersWithEncodedPasswords as $user)
        {
            $qb = $this->entityManager->createQuery('UPDATE App:User u SET u.password = :pass WHERE u.id = :id')
                ->setParameter('pass', $user->getPassword())
                ->setParameter('id', $user->getId());
            $qb->execute();
        }
    }
}