<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\UnavailableDates;
use App\Repository\UnavailableDatesRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<UnavailableDates>
 *
 * @method        UnavailableDates|Proxy create(array|callable $attributes = [])
 * @method static UnavailableDates|Proxy createOne(array $attributes = [])
 * @method static UnavailableDates|Proxy find(object|array|mixed $criteria)
 * @method static UnavailableDates|Proxy findOrCreate(array $attributes)
 * @method static UnavailableDates|Proxy first(string $sortedField = 'id')
 * @method static UnavailableDates|Proxy last(string $sortedField = 'id')
 * @method static UnavailableDates|Proxy random(array $attributes = [])
 * @method static UnavailableDates|Proxy randomOrCreate(array $attributes = [])
 * @method static UnavailableDatesRepository|RepositoryProxy repository()
 * @method static UnavailableDates[]|Proxy[] all()
 * @method static UnavailableDates[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static UnavailableDates[]|Proxy[] createSequence(array|callable $sequence)
 * @method static UnavailableDates[]|Proxy[] findBy(array $attributes)
 * @method static UnavailableDates[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static UnavailableDates[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class UnavailableDatesFactory extends ModelFactory
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
            'notes' => self::faker()->text(),
            'start_date' => self::faker()->dateTime(),
            'end_date' => self::faker()->dateTime(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(UnavailableDates $unavailableDates): void {})
        ;
    }

    protected static function getClass(): string
    {
        return UnavailableDates::class;
    }
}
