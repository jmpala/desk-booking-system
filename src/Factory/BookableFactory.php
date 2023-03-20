<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Bookable;
use App\Repository\BookableRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Bookable>
 *
 * @method        Bookable|Proxy create(array|callable $attributes = [])
 * @method static Bookable|Proxy createOne(array $attributes = [])
 * @method static Bookable|Proxy find(object|array|mixed $criteria)
 * @method static Bookable|Proxy findOrCreate(array $attributes)
 * @method static Bookable|Proxy first(string $sortedField = 'id')
 * @method static Bookable|Proxy last(string $sortedField = 'id')
 * @method static Bookable|Proxy random(array $attributes = [])
 * @method static Bookable|Proxy randomOrCreate(array $attributes = [])
 * @method static BookableRepository|RepositoryProxy repository()
 * @method static Bookable[]|Proxy[] all()
 * @method static Bookable[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Bookable[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Bookable[]|Proxy[] findBy(array $attributes)
 * @method static Bookable[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Bookable[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class BookableFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'code' => self::faker()->text(255),
            'description' => self::faker()->text(),
            'pos_x' => self::faker()->randomNumber(),
            'pos_y' => self::faker()->randomNumber(),
            'width' => 200,
            'height' => 100,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Bookable $bookable): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Bookable::class;
    }
}
