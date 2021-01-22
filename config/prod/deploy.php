<?php

use EasyCorp\Bundle\EasyDeployBundle\Deployer\DefaultDeployer;

return new class extends DefaultDeployer
{
    public function configure()
    {
        return $this->getConfigBuilder()
            ->server('my-vps')
            ->deployDir('/var/www/weather-app')
            ->repositoryUrl('git@github.com:MWaszczuk/weather-app.git')
            ->repositoryBranch('master')
        ;
    }

    public function beforePreparing()
    {
        $this->runRemote('cp {{ deploy_dir }}/repo/.env {{ project_dir }}/.env');
    }

    public function beforeFinishingDeploy()
    {
        $this->runRemote('test -f {{ deploy_dir }}/shared/.env.local || touch {{ deploy_dir }}/shared/.env.local');
        $this->runRemote('ln -s {{ deploy_dir }}/shared/.env.local {{ project_dir }}/.env.local');
        $this->runRemote('./bin/console doctrine:migrations:migrate --no-interaction');
    }
};
