<?php
	$file =  $_POST['command'];
	$lines = explode(PHP_EOL, $file);

	$events = array();
	for ($i = 0; $i < count($lines); $i++) {
		if (strpos($lines[$i], ':')) {
			$boom = explode(' ', $lines[$i]);
			$process = getProcess($lines, $i);
			if ($process) {
				$events[] = array(
					'time'		=> substr($boom[0], 0, -1),
					'event'		=> $boom[1],
					'acting-on' => $boom[3],
					'process'	=> $process,
				);
			}
		}
	}

	for ($i = 0; $i < count($events) - 1; $i++) {
		$events[$i]['duration'] = $events[$i + 1]['time'] - $events[$i]['time'];
	}

function getProcess($lines, $start) {
	$next = $start + 1;
	if (strpos($lines[$next], 'selects')) {
		$boom = explode(" ", $lines[$next]);
		return $boom[3];
	} else {
		return false;
	}
}

function printEvent($e) {
	return $e['time'] . 'ms: ' . $e['event'] . ' on ' . $e['acting-on'] . '; ' . $e['process'] . ' scheduled';
}

?>



<!doctype html>
<html>
	<head>
		<title>View &middot; Vischedualiser</title>
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-76719847-6"></script>
		<script>
		    window.dataLayer = window.dataLayer || [];
		    function gtag(){dataLayer.push(arguments);}
		    gtag('js', new Date());

		    gtag('config', 'UA-76719847-6');
		</script>
		<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" />
		<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
		<script>
			window.addEventListener("load", function(){
				window.cookieconsent.initialise({
				    "palette": {
			  			"popup": {
			    			"background": "#000"
				    	},
				    	"button": {
				        	"background": "#aaa"
				    	}
					},
					"theme": "edgeless"
				});
			});
		</script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://fonts.googleapis.com/css?family=Fira+Mono" rel="stylesheet" type="text/css">
		<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
		<style>
			body {
				font-family: "Fira Mono", monospace;
				display: flex;
				justify-content: flex-start;
				height: 100vh;
				white-space: nowrap;
				background-color: #222;
				color: #eee;
			}

			.container {
				margin: 45vh 200px;
			}

			p.meta {
				color: #666;
			}
			
			.meta a {
				color: #eee;
			}

			.cpu div {
				display: inline-block;
				border: 2px solid #eee;
				border-radius: 2px;
				transition: min-width 1s;
				font-size: 40px;
				font-weight: 600;
				overflow-x: hidden;
				transition: min-width 0.5s;
				min-width: 0;
			}

			.cpu div.unrealistic {
				min-width: 50px;
			}

			.cpu div.realistic:hover {
				min-width: 50px;
			}

			.cpu div.unrealistic, .cpu div.realistic {
				border-bottom-width: 4px;
			}

			.command {
				width: 30vw;
			}

			.command p {
				color: #666;
				margin: 0;
			}

			div:hover {
				cursor: pointer;
			}

			@media only screen and (max-width: 700px) {
				.container {
					margin: 30vh 0;
				}
			}
		</style>
	</head>
	<body>
		<a href="//github.com/guym4c/vischedualiser" target="_blank" class="github-corner" aria-label="View source on Github"><svg width="80" height="80" viewBox="0 0 250 250" style="fill:#eee; color:#222; position: fixed; top: 0; border: 0; right: 0;" aria-hidden="true"><path d="M0,0 L115,115 L130,115 L142,142 L250,250 L250,0 Z"></path><path d="M128.3,109.0 C113.8,99.7 119.0,89.6 119.0,89.6 C122.0,82.7 120.5,78.6 120.5,78.6 C119.2,72.0 123.4,76.3 123.4,76.3 C127.3,80.9 125.5,87.3 125.5,87.3 C122.9,97.6 130.6,101.9 134.4,103.2" fill="currentColor" style="transform-origin: 130px 106px;" class="octo-arm"></path><path d="M115.0,115.0 C114.9,115.1 118.7,116.5 119.8,115.4 L133.7,101.6 C136.9,99.2 139.9,98.4 142.2,98.6 C133.8,88.0 127.5,74.4 143.8,58.0 C148.5,53.4 154.0,51.2 159.7,51.0 C160.3,49.4 163.2,43.6 171.4,40.1 C171.4,40.1 176.1,42.5 178.8,56.2 C183.1,58.6 187.2,61.8 190.9,65.4 C194.5,69.0 197.7,73.2 200.1,77.6 C213.8,80.2 216.3,84.9 216.3,84.9 C212.7,93.1 206.9,96.0 205.4,96.6 C205.1,102.4 203.0,107.8 198.3,112.5 C181.9,128.9 168.3,122.5 157.7,114.1 C157.9,116.9 156.7,120.9 152.7,124.9 L141.0,136.5 C139.8,137.7 141.6,141.9 141.8,141.8 Z" fill="currentColor" class="octo-body"></path></svg></a><style>.github-corner:hover .octo-arm{animation:octocat-wave 560ms ease-in-out}@keyframes octocat-wave{0%,100%{transform:rotate(0)}20%,60%{transform:rotate(-25deg)}40%,80%{transform:rotate(10deg)}}@media (max-width:500px){.github-corner:hover .octo-arm{animation:none}.github-corner .octo-arm{animation:octocat-wave 560ms ease-in-out}}</style>
		<div class="container">
			<?php if(!isset($_GET['debug'])): ?>
				<p class="meta"><a href="#" id="realistic">Toggle realistic scale</a> <span title="Hover on CPU timeline, or add ?debug to request">Hover for debug info</p>
				<div class="cpu">
					<?php foreach($events as $event): ?>
						<div title="<?php echo printEvent($event); ?>"
							<?php if(empty($_GET['realistic']) && trim($event['process']) != 'null'):?> 
								class="unrealistic" 
							<?php endif; ?> 
							style="width: <?php echo $event['duration']; ?>px;">
							<?php if (trim($event['process']) != 'null'):
								echo htmlspecialchars($event['process']);
							endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="command">
					<?php foreach($lines as $line): ?>
						<p><?php echo $line; ?></p>
					<?php endforeach; ?>
				</div>
			<?php else: ?>
				<div class="command">
					<pre>
<?php echo print_r($events); ?>
					</pre>
				</div>
			<?php endif; ?>
		</div>
		<script>
			var realistic = false;
			$('#realistic').click(function() {
				if (!realistic) {
					$('.unrealistic').addClass('realistic');
					$('.unrealistic').removeClass('unrealistic');
				} else {
					$('.realistic').addClass('unrealistic');
					$('.realistic').removeClass('realistic');
				}
				realistic = !realistic;
			});
		</script>
	</body>
</html>
