<?php

namespace App\Repository;

use App\Entity\Address;
use App\Entity\PhoneNumber;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use function get_class;


/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private $userPasswordHasher;

    public function __construct(ManagerRegistry $registry, UserPasswordHasherInterface $userPasswordHasher)
    {
        parent::__construct($registry, User::class);
        $this->userPasswordHasher = $userPasswordHasher;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function store(array $data)
    {
        $user = new User();
        $user->setFirstName($data['first_name'])
            ->setLastName($data['last_name'])
            ->setEmail($data['email'])
            ->setRoles([$data['roles']])
            ->setUName(mt_rand(10000000, 99999999))
            ->setDob(DateTime::createFromFormat('Y-m-d', $data['dob']));
        $hashedPassword = $this->userPasswordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        foreach ($data['address'] as $addressData) {
            $address = new Address();
            $address->setStreet($addressData['street']);
            $address->setZip($addressData['zip']);
            $address->setCity($addressData['city']);
            $address->setCountry($addressData['country']);
            $user->addAddress($address);
            $this->_em->persist($address);
        }
        foreach ($data['phone'] as $phoneData) {
            $phoneNumber = new PhoneNumber();
            $phoneNumber->setPhoneNo($phoneData['number']);
            $user->addPhoneNumber($phoneNumber);
            $this->_em->persist($phoneNumber);
        }

        $this->_em->persist($user);
        $this->_em->flush();
        return $user;
    }

    public function update(User $user, array $data)
    {
        empty($data['firstName']) ? true : $user->setFirstName($data['first_name']);
        empty($data['last_name']) ? true : $user->setLastName($data['last_name']);
        empty($data['email']) ? true : $user->setEmail($data['email']);
        empty($data['roles']) ? true : $user->setRoles([$data['roles']]);
        empty($data['password']) ? true : $user->setPassword($this->userPasswordHasher->hashPassword($user, $data['password']));
        empty($data['dob']) ? true : $user->setDob(DateTime::createFromFormat('Y-m-d', $data['dob']));
        if (!empty($data['address'])) {
            foreach ($data['address'] as $addressData) {
                $address = new Address();
                $address->setStreet($addressData['street']);
                $address->setZip($addressData['zip']);
                $address->setCity($addressData['city']);
                $address->setCountry($addressData['country']);
                $user->addAddress($address);
                $this->_em->persist($address);
            }
        }
        if (!empty($data['phone'])) {
            foreach ($data['phone'] as $phoneData) {
                $phoneNumber = new PhoneNumber();
                $phoneNumber->setPhoneNo($phoneData['number']);
                $user->addPhoneNumber($phoneNumber);
                $this->_em->persist($phoneNumber);
            }
        }
        $this->_em->persist($user);
        $this->_em->flush();
        return $user;
    }

    /**
     * @return User[] Returns an array of User objects
     */

    public function findByKeyWord($keyword)
    {
        $builder = $this->createQueryBuilder('u');
        if (!empty($keyword)) {
            $builder->orWhere('u.first_name LIKE :firstName')
                ->orWhere('u.last_name LIKE :lastName')
                ->setParameter('firstName', "%{$keyword}%")
                ->setParameter('lastName', "%{$keyword}%");
        }
        return $builder->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
