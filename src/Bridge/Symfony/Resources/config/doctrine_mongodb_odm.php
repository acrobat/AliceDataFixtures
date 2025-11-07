<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function(ContainerConfigurator $container) {
    $services = $container->services();

    $services->alias('fidry_alice_data_fixtures.loader.doctrine_mongodb', 'fidry_alice_data_fixtures.doctrine_mongodb.purger_loader')
        ->public();

    // Deprecated (see DeprecateServicesPass)
    $services->alias('fidry_alice_data_fixtures.doctrine_mongodb.loader', 'fidry_alice_data_fixtures.loader.doctrine_mongodb');

    $services->set('fidry_alice_data_fixtures.doctrine_mongodb.purger_loader', \Fidry\AliceDataFixtures\Loader\PurgerLoader::class)
        ->lazy()
        ->args([
            service('fidry_alice_data_fixtures.doctrine_mongodb.persister_loader'),
            service('fidry_alice_data_fixtures.persistence.purger_factory.doctrine_mongodb'),
            '%fidry_alice_data_fixtures.default_purge_mode%',
        ]);

    $services->set('fidry_alice_data_fixtures.doctrine_mongodb.persister_loader', \Fidry\AliceDataFixtures\Loader\PersisterLoader::class)
        ->lazy()
        ->args([
            service('fidry_alice_data_fixtures.loader.simple'),
            service('fidry_alice_data_fixtures.persistence.persister.doctrine_mongodb'),
            service('logger')->ignoreOnInvalid(),
            // Processors are injected via a Compiler pass
        ]);

    $services->alias('fidry_alice_data_fixtures.persistence.purger_factory.doctrine_mongodb', 'fidry_alice_data_fixtures.persistence.doctrine_mongodb.purger.purger_factory')
        ->public();

    $services->set('fidry_alice_data_fixtures.persistence.doctrine_mongodb.purger.purger_factory', \Fidry\AliceDataFixtures\Bridge\Doctrine\Purger\Purger::class)
        ->lazy()
        ->args([service('doctrine_mongodb.odm.document_manager')]);

    // Deprecated (see DeprecateServicesPass)
    $services->alias('fidry_alice_data_fixtures.persistence.purger.doctrine_mongodb.odm_purger', 'fidry_alice_data_fixtures.persistence.doctrine_mongodb.purger.purger_factory');

    $services->alias('fidry_alice_data_fixtures.persistence.persister.doctrine_mongodb', 'fidry_alice_data_fixtures.persistence.persister.doctrine_mongodb.object_manager_persister')
        ->public();

    $services->set('fidry_alice_data_fixtures.persistence.persister.doctrine_mongodb.object_manager_persister', \Fidry\AliceDataFixtures\Bridge\Doctrine\Persister\ObjectManagerPersister::class)
        ->lazy()
        ->args([service('doctrine_mongodb.odm.document_manager')]);
};
