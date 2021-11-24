<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Modele\Modele;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(
        ManagerRegistry $registry,
        Security $security
    )
    {
        parent::__construct($registry, Sortie::class);
        $this->security = $security;
    }

    //Gestion des filtres
    public function findByFiltre(Modele $modele){
        $queryBuilder = $this->createQueryBuilder('s');
        //Filtre nom du campus
        if (!empty($modele->getNomCampus())){
            $queryBuilder->andWhere('s.campus = :nc');
            $queryBuilder->setParameter('nc', $modele->getNomCampus());
        }
        //Filtre Nom de la sortie contient
        if (!empty($modele->getNomSortie())){
            $queryBuilder->andWhere('s.nom LIKE % :ns %');
            $queryBuilder->setParameter('ns', $modele->getNomSortie());
        }
        //Filtres dates
        if (!empty($modele->getDateSortie1()) && !empty($modele->getDateSortie2())){
            if ($modele->getDateSortie1() < $modele->getDateSortie2()) {
                $queryBuilder->andWhere('s.dateHeureDebut BETWEEN :d1 AND :d2');
                $queryBuilder->setParameter('d1', $modele->getDateSortie1());
                $queryBuilder->setParameter('d2', $modele->getDateSortie2());
            }
        }
        //Filtre Je suis organisateur
        if (!empty($modele->getOrganisateur())){
            if($modele->getOrganisateur() == true){
                $queryBuilder->andWhere('s.organisateur =  :o');
                $queryBuilder->setParameter('o', $this->security->getUser());
            }
        }
        //Filtre Je suis inscrit
        if (!empty($modele->getInscrit())){
            if($modele->getInscrit() == true){
                $queryBuilder->andWhere(':user member of s.participants');
                $queryBuilder->setParameter('user', $this->security->getUser());
            }
        }
        //Filtre Je ne suis pas inscrit
        if (!empty($modele->getPasInscrit())){
            if($modele->getPasInscrit() == true){
                $queryBuilder->andWhere(':u not member of s.participants');
                $queryBuilder->setParameter('u', $this->security->getUser());
            }
        }
        //Filtre Sorties passees
        if (!empty($modele->getSortiePassees())){
            if($modele->getSortiePassees() == true){
                $queryBuilder->andWhere('s.etat.libelle = :sp');
                $queryBuilder->setParameter('sp', 'Passée');
            }
        }
        $query = $queryBuilder->getQuery();
        $query->setMaxResults(7);
        $results = $query->getResult();
        dump($results);


        return $results;
    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
