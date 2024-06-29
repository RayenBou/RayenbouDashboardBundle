<?php

namespace Rayenbou\DashboardBundle\Command;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'rayenbou:security',
    description: 'Creates a api configuration for the dashboard bundle.',
    hidden: false,

)]
class SecurityCommand extends Command
{



    protected function execute(InputInterface $input, OutputInterface $output): int
    {



        $securityConfigPath = 'config/packages/security.yaml';
        $config = Yaml::parseFile($securityConfigPath);

        $newProvidersData = [
            'app_api_provider' => [
                'entity' => [
                    'class' => 'Rayenbou\DashboardBundle\Entity\ApiUser',
                    'property' => 'email'
                ]
            ]
        ];
        if (isset($config['security']['providers'])) {

            $config['security']['providers'] = array_merge($newProvidersData, $config['security']['providers']);
        } else {
            $config['security']['providers'] = $newProvidersData;
        }


        $newFirewallsData = [
            'login' => [
                'pattern' => '^/api/login',
                'stateless' => true,
                'json_login' => [
                    'check_path' => '/api/login_check',
                    'success_handler' => 'lexik_jwt_authentication.handler.authentication_success',
                    'failure_handler' => 'lexik_jwt_authentication.handler.authentication_failure',
                    'provider' => 'app_api_provider'
                ]
            ],
            'api' => [
                'pattern' => '^/api',
                'stateless' => true,
                'jwt' => [
                    'provider' => 'app_api_provider',
                ]
            ]

        ];



        if (isset($config['security']['firewalls'])) {

            $config['security']['firewalls'] = array_merge($newFirewallsData, $config['security']['firewalls']);
        } else {
            $config['security']['firewalls'] = $newFirewallsData;
        }



        $newYaml = Yaml::dump($config, 4, 2);
        file_put_contents($securityConfigPath, $newYaml);

        $output->writeln([
            '',
            '',
            '<info>==========================</>',
            '',
            '<info>Security configuration updated successfully.</>',
            '',
            '<info>==========================</>',
            '',
            ''
        ]);

        return Command::SUCCESS;
    }
}
