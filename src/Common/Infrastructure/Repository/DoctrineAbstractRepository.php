<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Repository;

use App\Common\Core\Assert\Assert;
use App\Common\Infrastructure\Doctrine\Repository\TEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @template TEntity of object
 * @template TId
 *
 * @template-extends ServiceEntityRepository<TEntity>
 *
 * @method TEntity[]    findAll()
 */
abstract class DoctrineAbstractRepository extends ServiceEntityRepository
{
    /**
     * @phpstan-var class-string<TEntity>
     */
    protected string $entityClass;

    public function __construct(
        ManagerRegistry $registry,
        protected LoggerInterface $logger
    ) {
        if (!isset($this->entityClass)) {
            $this->entityClass = $this->guessEntityClass();
        }

        parent::__construct($registry, $this->entityClass);
    }

    /**
     * @return class-string<TEntity>
     */
    private function guessEntityClass(): string
    {
        $repoClass = static::class;

        $entityClass = preg_replace(
            [
                '/\\\\Repository\\\\/',
                '/Repository$/',
            ],
            [
                '\\Entity\\',
                '',
            ],
            $repoClass
        );

        Assert::classExists($entityClass);

        /** @var class-string<TEntity> $entityClass */
        return $entityClass;
    }

    /**
     * @param TEntity ...$entities
     */
    public function save(object ...$entities): void
    {
        $em = $this->getEntityManager();
        foreach ($entities as $entity) {
            $em->persist($entity);
        }

        $em->flush();
    }

    /**
     * @param TEntity ...$entities
     */
    public function delete(object ...$entities): void
    {
        if (!$entities) { // @phpstan-ignore-line
            return;
        }

        $em = $this->getEntityManager();
        foreach ($entities as $entity) {
            $em->remove($entity);
        }

        $em->flush();
    }

    /**
     * @param TEntity $entity
     */
    public function refresh(object $entity): void
    {
        $this->getEntityManager()->refresh($entity);
    }

    /**
     * @param TId $id
     * @return TEntity|null
     */
    public function getReference($id): ?object // phpcs:ignore
    {
        return $this->getEntityManager()->getReference($this->entityClass, $id);
    }

    /**
     * @param TId $id
     * @return TEntity
     */
    public function findOrFail($id): object // phpcs:ignore
    {
        $entity = $this->find($id);

        if (null === $entity) {
            throw new EntityNotFoundException(
                sprintf('%s entity with id "%s" not found.', $this->entityClass, (string)$id)
            );
        }

        return $entity;
    }

    /**
     * @param array<string, mixed> $criteria
     * @param array<string, 'ASC'|'DESC'>|null $orderBy
     *
     * @return TEntity
     */
    public function findOneByOrFail(array $criteria, ?array $orderBy = null): object
    {
        $entity = $this->findOneBy($criteria, $orderBy);

        if ($entity === null) {
            throw new EntityNotFoundException(
                sprintf(
                    '%s entity not found for criteria %s.',
                    $this->entityClass,
                    json_encode($criteria, JSON_THROW_ON_ERROR)
                )
            );
        }

        return $entity;
    }

    /**
     * @return array<string, mixed>
     */
    protected function extractQueryParameters(QueryBuilder $queryBuilder): array
    {
        $parameters = [];
        foreach ($queryBuilder->getParameters() as $parameter) {
            $parameters[$parameter->getName()] = $parameter->getValue();
        }

        return $parameters;
    }
}
