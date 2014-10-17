<?php
/*
 * Startpage for the labs
 * just links to all subfolders
 */

class Lab {
	public $dir;
	public $spec;
	public $title;
	public $repo_url;
	public $github_url;

	public function __construct( $dir, $spec_file ) {
		$this->dir = $dir;
		$this->spec = $this->has_spec( $spec_file ) ? json_decode(file_get_contents($this->dir.'/'.$spec_file)) : false;
		$this->title = $this->spec && $this->spec->name ? $this->spec->name : $this->dir;
		preg_match('/[a-z1-9]*\/[a-z1-9]*/', exec('cd '.$this->dir.' && git remote -v'), $this->repo_url);
		$this->github_url = $this->is_github_repo() ? 'http://github.com/'.$this->repo_url[0] : 0;
	}

	public function has_spec( $spec_file ){
		return file_exists($this->dir.'/'.$spec_file);
	}

	public function is_github_repo(){
		return file_exists($this->dir.'/.git') && preg_match('/git@github.com:[a-z1-9]*\/[a-z1-9]*\.git/', exec('cd '.$this->dir.' && git remote -v'));
	}
}

$settings = array(
	'spec_file' => 'lab.json',
	'exclude_dirs' => array('assets')
);

$dirs = glob('*', GLOB_ONLYDIR);

$labels = array(
	'github_url' => 'GitHub Repo'
);

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
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Yellon Labs</title>
	<style type="text/css">
		body {
			background: #eee;
			font-family: "Helvetica Neue", Helvetica, sans-serif;
		}

		img {
			max-width: 100%;
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
				$lab = new Lab( $dir, $settings['spec_file'] ); ?>
				<li>
					<header>
						<h2><a href="<?= $lab->dir ?>"><?= $lab->title ?></a></h2>
						<ul class="meta-data">
							<?php if( $lab->is_github_repo() ): ?>
								<li class="repo"><a href="<?= $lab->github_url ?>">GitHub</a></li>
							<?php endif; ?>
						</ul>
					</header>
					<?php if( $lab->has_spec( $settings['spec_file'] ) && isset($lab->spec->description) ): ?>
						<p><?= $lab->spec->description ?></p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</body>
</html>