<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function(ContainerConfigurator $container) {
    $services = $container->services();

    $services->alias('fidry_alice_data_fixtures.loader.doctrine_phpcr', 'fidry_alice_data_fixtures.doctrine_phpcr.persister_loader')
        ->public();

    // Deprecated (see DeprecateServicesPass)
    $services->alias('fidry_alice_data_fixtures.doctrine_phpcr.loader', 'fidry_alice_data_fixtures.loader.doctrine_phpcr');

    $services->set('fidry_alice_data_fixtures.doctrine_phpcr.purger_loader', \Fidry\AliceDataFixtures\Loader\PurgerLoader::class)
        ->lazy()
        ->args([
            service('fidry_alice_data_fixtures.doctrine_phpcr.persister_loader'),
            service('fidry_alice_data_fixtures.persistence.purger_factory.doctrine_phpcr'),
            '%fidry_alice_data_fixtures.default_purge_mode%',
            service('logger')->ignoreOnInvalid(),
        ]);

    $services->set('fidry_alice_data_fixtures.doctrine_phpcr.persister_loader', \Fidry\AliceDataFixtures\Loader\PersisterLoader::class)
        ->lazy()
        ->args([
            service('fidry_alice_data_fixtures.loader.simple'),
            service('fidry_alice_data_fixtures.persistence.persister.doctrine_phpcr'),
            service('logger')->ignoreOnInvalid(),
        ]);

    $services->alias('fidry_alice_data_fixtures.persistence.purger_factory.doctrine_phpcr', 'fidry_alice_data_fixtures.persistence.doctrine_phpcr.purger.purger_factory')
        ->public();

    $services->set('fidry_alice_data_fixtures.persistence.doctrine_phpcr.purger.purger_factory', \Fidry\AliceDataFixtures\Bridge\Doctrine\Purger\Purger::class)
        ->lazy()
        ->args([service('doctrine_phpcr.odm.document_manager')]);

    # Deprecated (see DeprecateServicesPass)
    $services->set('fidry_alice_data_fixtures.persistence.purger.doctrine_phpcr.odm_purger', \Fidry\AliceDataFixtures\Bridge\Doctrine\Purger\Purger::class)
        ->lazy()
        ->args([service('doctrine_phpcr.odm.document_manager')]);

    $services->alias('fidry_alice_data_fixtures.persistence.persister.doctrine_phpcr', 'fidry_alice_data_fixtures.persistence.persister.doctrine_phpcr.object_manager_persister')
        ->public();

    $services->set('fidry_alice_data_fixtures.persistence.persister.doctrine_phpcr.object_manager_persister', \Fidry\AliceDataFixtures\Bridge\Doctrine\Persister\ObjectManagerPersister::class)
        ->lazy()
        ->args([service('doctrine_phpcr.odm.document_manager')]);
};
