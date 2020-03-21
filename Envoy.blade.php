@setup
	require __DIR__.'/vendor/autoload.php';
	$dotenv = Dotenv\Dotenv::create(__DIR__);

	try {
		$dotenv->load();
		$dotenv->required(['DEPLOY_SERVER', 'DEPLOY_REPOSITORY', 'DEPLOY_PATH'])->notEmpty();
	} catch ( Exception $e )  {
		echo $e->getMessage();
	}

	$server = getenv('DEPLOY_SERVER');
	$repo = getenv('DEPLOY_REPOSITORY');
	$path = getenv('DEPLOY_PATH');
	$slack = getenv('DEPLOY_SLACK_WEBHOOK');
	$healthUrl = getenv('DEPLOY_HEALTH_CHECK');

    // Production Database
    $db_database = getenv('DB_DATABASE_PROD');
    $db_username = getenv('DB_USERNAME_PROD');
    $db_password = getenv('DB_PASSWORD_PROD');
    $app_url = getenv('APP_URL_PROD');

	if ( substr($path, 0, 1) !== '/' ) throw new Exception('Careful - your deployment path does not begin with /');

	$date = ( new DateTime )->format('Y-m-d--His');
	$env = isset($env) ? $env : "production";
	$branch = isset($branch) ? $branch : "master";
	$path = rtrim($path, '/');
	$release = $path.'/'.$date;
@endsetup

@servers(['localhost' => '127.0.0.1', 'web' => 'radu@94.237.83.127'])

{{-- test task --}}
@task('foo', ['on' => 'localhost'])
    ls -la
@endtask

{{-- test task --}}
@task('foo-web', ['on' => 'web'])
    ls -la
    echo $env;
@endtask

{{-- init task --}}
@task('init', ['on' => 'web'])
	if [ ! -d {{ $path }}/current ]; then
		cd {{ $path }}
		git clone {{ $repo }} --branch={{ $branch }} --depth=1 -q {{ $release }}
		echo "Repository cloned"
        echo "release={{ $release  }} path={{ $path }}";
		mv {{ $release }}/storage {{ $path }}/storage
        sudo chgrp -R www-data {{ $path }}/storage
        sudo chmod -R ug+rwx {{ $path }}/storage
		ln -s {{ $path }}/storage {{ $release }}/storage
		ln -s {{ $path }}/storage/public {{ $release }}/public/storage
		echo "Storage directory set up"
		cp {{ $release }}/.env.example {{ $path }}/.env
		ln -s {{ $path }}/.env {{ $release }}/.env
		echo "Environment file set up"
		rm -rf {{ $release }}
		echo "Deployment path initialised. Run 'envoy run deploy' now."
	else
		echo "Deployment path already initialised (current symlink exists)!"
	fi
@endtask

@story('deploy', ['on' => 'web'])
	deployment_start
	deployment_links
	deployment_composer
	deployment_migrate
	deployment_cache
	deployment_finish
	health_check
	deployment_option_cleanup
@endstory

@story('deploy_cleanup', ['on' => 'web'])
	deployment_start
	deployment_links
	deployment_composer
	deployment_migrate
	deployment_cache
	deployment_finish
	health_check
	deployment_cleanup
@endstory

@story('rollback', ['on' => 'web'])
	deployment_rollback
	health_check
@endstory

@task('deployment_start')
	cd {{ $path }}
	echo "Deployment ({{ $date }}) started"
	git clone {{ $repo }} --branch={{ $branch }} --depth=1 -q {{ $release }}
	echo "Repository cloned"
@endtask

@task('deployment_links')
	cd {{ $path }}
	rm -rf {{ $release }}/storage
	ln -s {{ $path }}/storage {{ $release }}/storage
	ln -s {{ $path }}/storage/public {{ $release }}/public/storage
	echo "Storage directories set up"

    sed -i "s/APP_ENV=.*/APP_ENV=production/" {{ $path }}/.env;
    sed -i "s/APP_DEBUG=.*/APP_DEBUG=true/" {{ $path }}/.env;
    sed -i "s/APP_URL=.*/APP_URL={{ $app_url }}/" {{ $path }}/.env;
    sed -i "s/DB_DATABASE=.*/DB_DATABASE={{ $db_database }}/" {{ $path }}/.env;
    sed -i "s/DB_USERNAME=.*/DB_USERNAME={{ $db_username }}/" {{ $path }}/.env;
    sed -i "s/DB_PASSWORD=.*/DB_PASSWORD={{ $db_password }}/" {{ $path }}/.env;
	ln -s {{ $path }}/.env {{ $release }}/.env
	echo "Environment file set up"
@endtask

@task('deployment_composer')
	echo "Installing composer depencencies..."
	cd {{ $release }}
	composer install --no-interaction --quiet --no-dev --prefer-dist --optimize-autoloader
@endtask

@task('deployment_migrate')
	php {{ $release }}/artisan migrate --env={{ $env }} --force --no-interaction --seed
@endtask

@task('deployment_cache')
	php {{ $release }}/artisan view:clear --quiet
	php {{ $release }}/artisan cache:clear --quiet
	php {{ $release }}/artisan config:cache --quiet
	echo "Cache cleared"
@endtask

@task('deployment_finish')
	php {{ $release }}/artisan queue:restart --quiet
	echo "Queue restarted"
	ln -nfs {{ $release }} {{ $path }}/current
	echo "Deployment ({{ $date }}) finished"
@endtask

@task('deployment_cleanup')
	cd {{ $path }}
	find . -maxdepth 1 -name "20*" | sort | head -n -4 | xargs rm -Rf
	echo "Cleaned up old deployments"
@endtask

@task('deployment_option_cleanup')
	cd {{ $path }}
	@if ( isset($cleanup) && $cleanup )
		find . -maxdepth 1 -name "20*" | sort | head -n -4 | xargs rm -Rf
		echo "Cleaned up old deployments"
	@endif
@endtask


@task('health_check')
	@if ( ! empty($healthUrl) )
		if [ "$(curl --write-out "%{http_code}\n" --silent --output /dev/null {{ $healthUrl }})" == "200" ]; then
			printf "\033[0;32mHealth check to {{ $healthUrl }} OK\033[0m\n"
		else
			printf "\033[1;31mHealth check to {{ $healthUrl }} FAILED\033[0m\n"
		fi
	@else
		echo "No health check set"
	@endif
@endtask


@task('deployment_rollback')
	cd {{ $path }}
	ln -nfs {{ $path }}/$(find . -maxdepth 1 -name "20*" | sort  | tail -n 2 | head -n1) {{ $path }}/current
	echo "Rolled back to $(find . -maxdepth 1 -name "20*" | sort  | tail -n 2 | head -n1)"
@endtask

@task('key_generate', ['on' => 'web'])
    php {{ $path }}/current/artisan key:generate --force
    php {{ $path }}/current/artisan cache:clear
    php {{ $path }}/current/artisan config:cache
    php {{ $path }}/current/artisan jwt:secret
@endtask
