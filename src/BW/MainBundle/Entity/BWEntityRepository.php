<?php

namespace BW\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * BW Entity Repository with common usefull methods
 */
class BWEntityRepository extends EntityRepository
{
    
//    public function setCriteria(\Doctrine\ORM\QueryBuilder $qb, array $criteria = array()) {
//        
//        foreach ($criteria as $criterion) {
////            $key        = $criterion[0];
////            $statement  = $criterion[1];
////            $value      = $criterion[2];
////            
////            $qb->where("t.$key $statement :$key");
////            $qb->setParameter($key, $value);
//
//            $qb->where($cri);
//            $qb->setParameter($key, $value);
//        }
//        
//        return $qb;
//    }
    
    /**
     * Поиск следующего по критериям элемента
     * @param array $criteria = массивам с параметрами array(field, statement, value)
     * @return mixed
     */
    public function findPrevById($id) {
        $qb = $this->createQueryBuilder('t');
        
        $query = $qb
                ->select()
                ->where("t.id < :id")
                ->setParameter('id', (int)$id)
                ->orderBy('t.id', 'desc')
                ->setMaxResults(1)
                ->getQuery();
        
        $result = $query->getOneOrNullResult();
        
        if ($result === NULL) {
            return $this->findLastById();
        }
        
        return $result;
    }
    
    /**
     * Поиск следующего по критериям элемента
     * @param array $criteria = массивам с параметрами array(field, statement, value)
     * @return mixed
     */
    public function findNextById($id) {
        $qb = $this->createQueryBuilder('t');
        
        $query = $qb
                ->select()
                ->where("t.id > :id")
                ->setParameter('id', (int)$id)
                ->orderBy('t.id', 'asc')
                ->setMaxResults(1)
                ->getQuery();
        
        $result = $query->getOneOrNullResult();
        
        if ($result === NULL) {
            return $this->findFirstById();
        }
        
        return $result;
    }
    
    /**
     * Поиск первого элемента
     * @return mixed
     */
    public function findFirstById() {
        $qb = $this->createQueryBuilder('t');
        
        $query = $qb
                ->select()
                ->orderBy('t.id', 'asc')
                ->setMaxResults(1)
                ->getQuery();
        
        $result = $query->getOneOrNullResult();
        
        return $result;
    }
    
    /**
     * Поиск последнего элемента
     * @return mixed
     */
    public function findLastById() {
        $qb = $this->createQueryBuilder('t');
        
        $query = $qb
                ->select()
                ->orderBy('t.id', 'desc')
                ->setMaxResults(1)
                ->getQuery();
        
        $result = $query->getOneOrNullResult();
        
        return $result;
    }
}