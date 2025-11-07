<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function(ContainerConfigurator $container) {
    $services = $container->services();

    $services->alias('fidry_alice_data_fixtures.loader.doctrine', 'fidry_alice_data_fixtures.doctrine.purger_loader')
        ->public();

    // Deprecated (see DeprecateServicesPass)
    $services->alias('fidry_alice_data_fixtures.doctrine.loader', 'fidry_alice_data_fixtures.loader.doctrine');

    $services->set('fidry_alice_data_fixtures.doctrine.purger_loader', \Fidry\AliceDataFixtures\Loader\PurgerLoader::class)
        ->lazy()
        ->args([
            service('fidry_alice_data_fixtures.doctrine.persister_loader'),
            service('fidry_alice_data_fixtures.persistence.purger_factory.doctrine'),
            '%fidry_alice_data_fixtures.default_purge_mode%',
            service('logger')->ignoreOnInvalid(),
        ]);

    $services->set('fidry_alice_data_fixtures.doctrine.persister_loader', \Fidry\AliceDataFixtures\Loader\PersisterLoader::class)
        ->lazy()
        ->args([
            service('fidry_alice_data_fixtures.loader.simple'),
            service('fidry_alice_data_fixtures.persistence.persister.doctrine'),
            service('logger')->ignoreOnInvalid(),
            // Processors are injected via a Compiler pass
        ]);

    $services->alias('fidry_alice_data_fixtures.persistence.purger_factory.doctrine', 'fidry_alice_data_fixtures.persistence.doctrine.purger.purger_factory')
        ->public();

    $services->set('fidry_alice_data_fixtures.persistence.doctrine.purger.purger_factory', \Fidry\AliceDataFixtures\Bridge\Doctrine\Purger\Purger::class)
        ->lazy()
        ->args([service('doctrine.orm.entity_manager')]);

    // Deprecated (see DeprecateServicesPass)
    $services->alias('fidry_alice_data_fixtures.persistence.purger.doctrine.orm_purger', 'fidry_alice_data_fixtures.persistence.doctrine.purger.purger_factory');

    // Deprecated (see DeprecateServicesPass)
    $services->set('fidry_alice_data_fixtures.persistence.purger_modepurger_mode', \Fidry\AliceDataFixtures\Persistence\PurgeMode::class)
        ->private()
        ->factory([\Fidry\AliceDataFixtures\Persistence\PurgeMode::class, 'createDeleteMode']);

    $services->alias('fidry_alice_data_fixtures.persistence.persister.doctrine', 'fidry_alice_data_fixtures.persistence.persister.doctrine.object_manager_persister')
        ->public();

    $services->set('fidry_alice_data_fixtures.persistence.persister.doctrine.object_manager_persister', \Fidry\AliceDataFixtures\Bridge\Doctrine\Persister\ObjectManagerPersister::class)
        ->lazy()
        ->args([service('doctrine.orm.entity_manager')]);
};
