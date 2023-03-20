<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Bookings;
use App\Repository\BookingsRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Bookings>
 *
 * @method        Bookings|Proxy create(array|callable $attributes = [])
 * @method static Bookings|Proxy createOne(array $attributes = [])
 * @method static Bookings|Proxy find(object|array|mixed $criteria)
 * @method static Bookings|Proxy findOrCreate(array $attributes)
 * @method static Bookings|Proxy first(string $sortedField = 'id')
 * @method static Bookings|Proxy last(string $sortedField = 'id')
 * @method static Bookings|Proxy random(array $attributes = [])
 * @method static Bookings|Proxy randomOrCreate(array $attributes = [])
 * @method static BookingsRepository|RepositoryProxy repository()
 * @method static Bookings[]|Proxy[] all()
 * @method static Bookings[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Bookings[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Bookings[]|Proxy[] findBy(array $attributes)
 * @method static Bookings[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Bookings[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class BookingsFactory extends ModelFactory
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
            'confirmation' => bin2hex(random_bytes(4)),
            'start_date' => new \DateTime(),
            'end_date' => new \DateTime(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Bookings $bookings): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Bookings::class;
    }
}
