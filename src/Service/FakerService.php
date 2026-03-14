<?php

namespace App\Service;

use DateTime;
use App\Entity\City;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Entity\Customer;
use App\Entity\Apartment;
use App\Entity\Reservation;
use App\Entity\Reservations;
use App\Entity\ReservationVehicle;
use App\Repository\UserRepository;
use App\Repository\CityRepository;
use App\Entity\ReservationApartment;
use App\Repository\StatusRepository;
use App\Repository\VehicleRepository;
use App\Repository\ApartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoryVehicleRepository;
use App\Repository\CategoryApartmentRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FakerService
{
    private $entityManager;
    private $passwordHasher;
    private $cityRepository;
    private $userRepository;
    private $statusRepository;
    private $vehicleRepository;
    private $apartmentRepository;
    private $categoryVehicleRepository;
    private $categoryApartmentRepository;

    public function __construct(
        CityRepository $cityRepository,
        UserRepository $userRepository,
        StatusRepository $statusRepository,
        VehicleRepository $vehicleRepository,
        EntityManagerInterface $entityManager,
        ApartmentRepository $apartmentRepository,
        UserPasswordHasherInterface $passwordHasher,
        CategoryVehicleRepository $categoryVehicleRepository,
        CategoryApartmentRepository $categoryApartmentRepository,)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->cityRepository = $cityRepository;
        $this->userRepository = $userRepository;
        $this->statusRepository = $statusRepository;
        $this->vehicleRepository = $vehicleRepository;
        $this->apartmentRepository = $apartmentRepository;
        $this->categoryVehicleRepository = $categoryVehicleRepository;
        $this->categoryApartmentRepository = $categoryApartmentRepository;
    }

    public function createAll(): void
    {
        $vehicles = json_decode(file_get_contents(__DIR__ . '/../../jsons/vehicles.json'), true);
        $apartments = json_decode(file_get_contents(__DIR__ . '/../../jsons/apartments.json'), true);
        $customers = json_decode(file_get_contents(__DIR__ . '/../../jsons/customers.json'), true);
        $reservations = json_decode(file_get_contents(__DIR__ . '/../../jsons/reservations.json'), true);

        $this->createApartments($apartments);
        $this->createVehicles($vehicles);
        $this->createCustomers($customers);
        $this->createReservations($reservations);
    }

    public function createApartments(array $apartments): void
    {
        $index = 1;
        foreach($apartments as $data) {
            $apartment = new Apartment();
            $postalCode = $data['postal_code'];
            $city = $this->cityRepository->findOneBy(['postal_code' => $postalCode]);

            if (!$city) {
                $city = new City();
                $city
                    ->setName($data['city'])
                    ->setPostalCode($postalCode)
                    ->setCountry($data['country'])
                    ->setActive(true)
                ;
            }
            
            $apartment
                ->setCategory($this->categoryApartmentRepository->findOneBy(['name' => $data['category']]))
                ->setName($data['name'])
                ->setAddress($data['address'])
                ->setCity($city)
                ->setDescription($data['description'])
                ->setRooms($data['rooms'])
                ->setBathRooms($data['bath_rooms'])
                ->setArea($data['area'])
                ->setPrice($data['price'])
                ->setOwner($data['owner'])
                ->setAvailable(true)
                ->setPrincipalPicture($index . '.PNG')
                ->setActive(true)
            ;

            $this->entityManager->persist($apartment);
            $this->entityManager->persist($city);
            $this->entityManager->flush();
            $index++;
        }
    }

    public function createVehicles(array $vehicles): void
    {
        $index = 1;
        foreach ($vehicles as $data) {
            $vehicle = new Vehicle();
            $postalCode = $data['postal_code'];
            $city = $this->cityRepository->findOneBy(['postal_code' => $postalCode]);
            
            if (!$city) {
                $city = new City();
                $city
                    ->setName($data['city'])
                    ->setPostalCode($postalCode)
                    ->setCountry($data['country'])
                    ->setActive(true)
                ;
            }

            $vehicle
                ->setCategory($this->categoryVehicleRepository->findOneBy(['name' => $data['category']]))
                ->setBrand($data['brand'])
                ->setModel($data['model'])
                ->setYear($data['year'])
                ->setDescription($data['description'])
                ->setColor($data['color'])
                ->setMileage($data['mileage'])
                ->setPrice($data['price'])
                ->setAddress($data['address'])
                ->setCity($city)
                ->setAvailable(true)
                ->setPrincipalPicture($index . '.png')
                ->setActive(true)
            ;

            $this->entityManager->persist($vehicle);
            $this->entityManager->persist($city);
            $this->entityManager->flush();
            $index++;
        }
    }

    public function createCustomers(array $customers): void
    {
        foreach ($customers as $data) {
            $user = new User();
            $user
                ->setFirstname($data['firstname'])
                ->setLastname($data['lastname'])
                ->setEmail($data['email'])
                ->setPassword($this->passwordHasher->hashPassword(
                        $user, 'test'
                ))
                ->setRoles(['ROLE_CUSTOMER'])
                ->setActive(true)
            ;

            $customer = new Customer();
            $customer
                ->setUser($user)
                ->setPhone($data['phone'])
            ;

            $this->entityManager->persist($customer);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }

    public function createReservations(array $array): void
    {
        foreach ($array as $data) {
            $reservation = new Reservation();
            $reservations = new Reservations();
            $reservations->setCustomer($this->userRepository->findOneBy(['email' => $data['customer']])->getCustomer());
            $reservation
                ->setBeginDate(new DateTime($data['begin_date']))
                ->setEndDate(new DateTime(($data['end_date'])))
                ->setStatus($this->statusRepository->findOneBy(['name' => $data['status']]));
            ;
        
            if ($data['type'] == 'apartment') {
                $reservationApartment = new ReservationApartment();

                $reservationApartment->addApartment($this->apartmentRepository->findOneBy(['id' => $data['id']]));
                $reservationApartment->setReservation($reservation);
                $reservations->setReservationApartment($reservationApartment);

                $this->entityManager->persist($reservationApartment);
            } else {
                $reservationVehicle = new ReservationVehicle();

                $reservationVehicle->addVehicle($this->vehicleRepository->findOneBy(['id' => $data['id']]));
                $reservationVehicle->setReservation($reservation);
                $reservations->setReservationVehicle($reservationVehicle);

                $this->entityManager->persist($reservationVehicle);
            }

            $this->entityManager->persist($reservations);
            $this->entityManager->persist($reservation);
            $this->entityManager->flush();
        }
    }
}
