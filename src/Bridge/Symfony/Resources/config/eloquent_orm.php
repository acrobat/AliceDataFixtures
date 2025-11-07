<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function(ContainerConfigurator $container) {
    $services = $container->services();
    $parameters = $container->parameters();
    $parameters->set('fidry_alice_data_fixtures.eloquent.migration_path', 'migrations');

    $services->alias('fidry_alice_data_fixtures.loader.eloquent', 'fidry_alice_data_fixtures.eloquent.purger_loader')
        ->public();

    $services->alias('fidry_alice_data_fixtures.eloquent.loader', 'fidry_alice_data_fixtures.loader.eloquent');

    $services->set('fidry_alice_data_fixtures.eloquent.purger_loader', \Fidry\AliceDataFixtures\Loader\PurgerLoader::class)
        ->lazy()
        ->args([
            service('fidry_alice_data_fixtures.eloquent.persister_loader'),
            service('fidry_alice_data_fixtures.persistence.purger_factory.eloquent'),
            '%fidry_alice_data_fixtures.default_purge_mode%',
            service('logger')->ignoreOnInvalid(),
        ]);

    $services->set('fidry_alice_data_fixtures.eloquent.persister_loader', \Fidry\AliceDataFixtures\Loader\PersisterLoader::class)
        ->lazy()
        ->args([
            service('fidry_alice_data_fixtures.loader.simple'),
            service('fidry_alice_data_fixtures.persistence.persister.eloquent'),
            service('logger')->ignoreOnInvalid(),
        ]);

    $services->alias('fidry_alice_data_fixtures.persistence.purger_factory.eloquent', 'fidry_alice_data_fixtures.persistence.eloquent.purger.purger_factory')
        ->public();

    $services->set('fidry_alice_data_fixtures.persistence.eloquent.purger.purger_factory', \Fidry\AliceDataFixtures\Bridge\Eloquent\Purger\ModelPurger::class)
        ->lazy()
        ->args([
            service('wouterj_eloquent.migrations.repository'),
            '%fidry_alice_data_fixtures.eloquent.migration_path%',
            service('wouterj_eloquent.migrator'),
        ]);

    $services->alias('fidry_alice_data_fixtures.persistence.purger.eloquent.model_purger', 'fidry_alice_data_fixtures.persistence.eloquent.purger.purger_factory');

    $services->set('fidry_alice_data_fixtures.persistence.purger_mode', \Fidry\AliceDataFixtures\Persistence\PurgeMode::class)
        ->private()
        ->factory([\Fidry\AliceDataFixtures\Persistence\PurgeMode::class, 'createDeleteMode']);

    $services->alias('fidry_alice_data_fixtures.persistence.persister.eloquent', 'fidry_alice_data_fixtures.persistence.persister.eloquent.model_persister')
        ->public();

    $services->set('fidry_alice_data_fixtures.persistence.persister.eloquent.model_persister', \Fidry\AliceDataFixtures\Bridge\Eloquent\Persister\ModelPersister::class)
        ->lazy()
        ->args([service('wouterj_eloquent.database_manager')]);
};
