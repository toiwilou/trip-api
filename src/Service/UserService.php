<?php 

namespace App\Service;

use App\Entity\User;
use App\Traits\UserTrait;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UserService
{
    use UserTrait;

    private $params;
    private $entityManager;
    private $userRepository;
    private $passwordHasher;

    public function __construct(
        ParameterBagInterface $params,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher)
    {
        $this->params = $params;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function getAll(): array
    {
        return $this->userRepository->getAll();
    }

    public function getById(int $id): User
    {
        return $this->userRepository->findOneBy(['id' => $id]);
    }

    public function getByEmail(string $email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }

    public function persist(Request $request, User $user): void
    {
        $data = json_decode($request->getContent(), true);
        $user
            ->setFirstname($data['firstname'] ?? $user->getFirstname())
            ->setLastname($data['lastname'] ?? $user->getLastname())
            ->setEmail($data['email'] ?? $user->getEmail())
            ->setPassword($this->passwordHasher->hashPassword(
                $user, $data['password']
            ) ?? $user->getPassword())
            ->setActive($data['active'] == 'true' ?? $user->isActive())
            ->setRoles($this->getRoles($data['role']) ?? $user->getRoles())
            ->setPicture($data['picture'] ?? $user->getPicture())
            ->setResetToken($data['resset_token'] ?? $user->getResetToken())
        ;

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function persistCustomerUser(Request $request, User $user): User
    {
        $this->persist($request, $user);

        return $user;
    }

    public function add(Request $request): void
    {
        $this->persist($request, new User());
    }

    public function edit(Request $request, User $user): void
    {
        $this->persist($request, $user);
    }

    public function createFirstUser(): void
    {
        $email = $this->params->get('email_first_user');
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user)
        {
            $firstUser = new User();
            $firstUser
                ->setFirstname($this->params->get('firstname_first_user'))
                ->setLastname($this->params->get('lastname_first_user'))
                ->setEmail($email)
                ->setPassword($this->passwordHasher->hashPassword(
                        $firstUser, $this->params->get('password_user')
                ))
                ->setRoles($this->getRoles('ROLE_MANAGER'))
                ->setActive(true)
            ;

            $this->entityManager->persist($firstUser);
            $this->entityManager->flush();
        }
    }
}
