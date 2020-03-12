@servers(['localhost' => '127.0.0.1'])
@servers(['web' => 'radu@94.237.83.127'])

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

	if ( substr($path, 0, 1) !== '/' ) throw new Exception('Careful - your deployment path does not begin with /');

	$date = ( new DateTime )->format('YmdHis');
	$env = isset($env) ? $env : "production";
	$branch = isset($branch) ? $branch : "master";
	$path = rtrim($path, '/');
	$release = $path.'/'.$date;
@endsetup

@task('foo', ['on' => 'localhost'])
    ls -la
@endtask


@task('foo-web', ['on' => 'web'])
    ls -la
@endtask
