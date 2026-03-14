<?php 

namespace App\Service;

use App\Entity\Status;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class StatusService
{
    private $repository;
    private $entityManager;

    public function __construct(
        StatusRepository $statusRepository,
        EntityManagerInterface $entityManager)
    {
        $this->repository = $statusRepository;
        $this->entityManager = $entityManager;
    }

    public function getAll(): ?array
    {
        return $this->repository->getAll();
    }

    public function getById(int $id): Status
    {
        return $this->repository->findOneBy(['id' => $id]);
    }

    public function persist(Request $request, Status $status): void
    {
        $data = json_decode($request->getContent(), true);
        $status
            ->setName($data['name'] ?? $status->getName())
            ->setColor($data['color'] ?? $status->getColor())
            ->setActive($data['active'] == 'true' ?? $status->isActive())
        ;

        $this->entityManager->persist($status);
        $this->entityManager->flush();
    }

    public function add(Request $request): void
    {
        $this->persist($request, new Status());
    }

    public function edit(Request $request, Status $status): void
    {
        $this->persist($request, $status);
    }

    public function createAll(): void
    {
        $datas = json_decode(file_get_contents(__DIR__ . '/../../jsons/status.json'), true);
        
        foreach ($datas as $data) {
            $status = $this->repository->findOneBy(['name' => $data['name']]);

            if (!$status)
            {
                $status = new Status();
                $status
                    ->setActive(true)
                    ->setName($data['name'])
                    ->setColor($data['color'])
                ;

                $this->entityManager->persist($status);
                $this->entityManager->flush();
            }
        }
    }
}
