<?php

namespace App\Repository;

use App\Entity\UrlCodeEntity;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<UrlCodeEntity>
 *
 * @method UrlCodeEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method UrlCodeEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method UrlCodeEntity[]    findAll()
 * @method UrlCodeEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UrlCodeEntityRepository extends EntityRepository
{
}
