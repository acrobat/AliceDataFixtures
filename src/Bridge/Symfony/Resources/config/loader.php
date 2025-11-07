<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function(ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('fidry_alice_data_fixtures.loader.multipass_file', \Fidry\AliceDataFixtures\Loader\MultiPassLoader::class)
        ->lazy()
        ->args([service('nelmio_alice.file_loader')]);

    $services->set('fidry_alice_data_fixtures.loader.simple', \Fidry\AliceDataFixtures\Loader\SimpleLoader::class)
        ->lazy()
        ->args([
            service('nelmio_alice.files_loader'),
            service('logger')->ignoreOnInvalid(),
        ]);
};
