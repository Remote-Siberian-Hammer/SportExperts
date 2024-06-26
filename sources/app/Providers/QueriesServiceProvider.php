<?php

namespace App\Providers;

use App\Domain\Interfaces\Repositories\Entities\EventRepositoryInterface;
use App\Domain\Interfaces\Repositories\Entities\InvitedRepositoryInterface;
use App\Domain\Interfaces\Repositories\Entities\LoggingRepositoryInterface;
use App\Domain\Interfaces\Repositories\Entities\ParticipantRepositoryInterface;
use App\Domain\Interfaces\Repositories\Entities\OptionRepositoryInterface;
use App\Domain\Interfaces\Repositories\Entities\TeamRepositoryInterface;
use App\Domain\Interfaces\Repositories\Entities\TournamentRepositoryInterface;
use App\Domain\Interfaces\Repositories\Entities\TournamentValueRepositoryInterface;
use App\Domain\Interfaces\Repositories\Entities\UserRepositoryInterface;
use App\Domain\Interfaces\Repositories\LCRUD_OperationInterface;
use App\Repository\EventRepository;
use App\Repository\InvitedRepository;
use App\Repository\LoggingRepository;
use App\Repository\ParticipantRepository;
use App\Repository\OptionRepository;
use App\Repository\TeamRepository;
use App\Repository\TournamentRepository;
use App\Repository\TournamentValueRepository;
use App\Repository\UserRepository;
use Illuminate\Support\ServiceProvider;

class QueriesServiceProvider extends ServiceProvider
{
//    /**
//     * Register services.
//     */
//    public function register(): void
//    {
//        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
//    }
//
//    /**
//     * Bootstrap services.
//     */
//    public function boot(): void
//    {
//        //
//    }

    /**
     *
     * @var array
     */
    public $bindings = [
        UserRepositoryInterface::class              => UserRepository::class,
        TeamRepositoryInterface::class              => TeamRepository::class,
        EventRepositoryInterface::class             => EventRepository::class,
        ParticipantRepositoryInterface::class       => ParticipantRepository::class,
        OptionRepositoryInterface::class            => OptionRepository::class,
        TournamentRepositoryInterface::class        => TournamentRepository::class,
        TournamentValueRepositoryInterface::class   => TournamentValueRepository::class,
        LoggingRepositoryInterface::class           => LoggingRepository::class,
        InvitedRepositoryInterface::class           => InvitedRepository::class,
    ];

    /**
     * @var array
     */
    public $singletons = [];
}
