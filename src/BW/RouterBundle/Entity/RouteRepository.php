<?php

namespace BW\RouterBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class RouteRepository
 * @package BW\RouterBundle\Entity
 */
class RouteRepository extends EntityRepository
{
    
    public function findRouteBy($query, $locale = NULL)
    {
        $qb = $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.query = :query')
            ->setParameter('query', $query)
        ;
        
        if (NULL === $locale) {
            $qb
                ->andWhere('r.lang IS NULL')
            ;
        } else {
            $qb
                ->join('r.lang', 'l')
                ->andWhere('l.sign = :locale')
                ->setParameter('locale', $locale)
            ;
        }
        
        $qb->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
