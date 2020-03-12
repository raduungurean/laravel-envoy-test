<?php $release = isset($release) ? $release : null; ?>
<?php $branch = isset($branch) ? $branch : null; ?>
<?php $env = isset($env) ? $env : null; ?>
<?php $date = isset($date) ? $date : null; ?>
<?php $healthUrl = isset($healthUrl) ? $healthUrl : null; ?>
<?php $slack = isset($slack) ? $slack : null; ?>
<?php $path = isset($path) ? $path : null; ?>
<?php $repo = isset($repo) ? $repo : null; ?>
<?php $server = isset($server) ? $server : null; ?>
<?php $e = isset($e) ? $e : null; ?>
<?php $dotenv = isset($dotenv) ? $dotenv : null; ?>
<?php $__container->servers(['localhost' => '127.0.0.1']); ?>
<?php $__container->servers(['web' => 'radu@94.237.83.127']); ?>

<?php
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
?>

<?php $__container->startTask('foo', ['on' => 'localhost']); ?>
    ls -la
<?php $__container->endTask(); ?>


<?php $__container->startTask('foo-web', ['on' => 'web']); ?>
    ls -la
<?php $__container->endTask(); ?>
