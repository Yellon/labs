<?php
/*
 * Startpage for the labs
 * just links to all subfolders
 */

$settings = array(
	'spec_file' => 'lab.json',
	'exclude_dirs' => array('assets')
);

$dirs = glob('*', GLOB_ONLYDIR);

// Remove excluded dirs
foreach ( $settings['exclude_dirs'] as $exclude ) {
	$keys = array_keys( $dirs, $exclude );

	if ( $keys ) {
		foreach ( $keys as $key ) {
			array_splice( $dirs, $key, 1 );
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Yellon Labs</title>
	<style type="text/css">
		body {
			background: #eee;
			font-family: "Helvetica Neue", Helvetica, sans-serif;
		}

		h1 {
			text-transform: uppercase;
			letter-spacing: 1px;
		}

		ul {
			list-style: none;
			padding-left: 0;
		}

		.wrapper {
			max-width: 960px;
			margin: 0 auto;
		}
	</style>
</head>
<body>
	<div class="wrapper">
		<h1>Yellon Labs</h1>
		<ul>
			<?php foreach(  $dirs as $dir ):
				$has_spec = file_exists($dir.'/'.$settings['spec_file']);
				$lab_data = $has_spec ? json_decode(file_get_contents($dir.'/'.$settings['spec_file'])) : false; 
				$title = $lab_data && $lab_data->name ? $lab_data->name : $dir;  ?>
				<li>
					<h2><a href="<?= $dir ?>"><?= $title ?></a></h2>
					<?php if( isset($lab_data->description) ): ?>
						<p><?= $lab_data->description ?></p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</body>
</html>