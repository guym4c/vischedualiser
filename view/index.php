<?php
	$file =  $_POST['command'];
	$lines = explode(PHP_EOL, $file);

	// remove chaff
	$for = count($lines);
	$events = array();
	for ($i = 0; $i < $for; $i++) {
		if (strpos($lines[$i], ':')) {
			$boom = explode(' ', $lines[$i]);
			$process = getProcess($lines, $i);
			if ($process) {
				$events[] = array(
					'time'		=> substr($boom[0], 0, -1),
					'event'		=> $boom[1],
					'process'	=> $process
				);
			}
		}
	}

	for ($i = 0; $i < count($events) - 1; $i++) {
		$events[$i]['duration'] = $events[$i + 1]['time'] - $events[$i]['time'];
	}

	// print_r($events);

function getProcess($lines, $start) {
	$next = $start + 1;
	if (strpos($lines[$next], 'selects')) {
		$boom = explode(" ", $lines[$next]);
		return $boom[3];
	} else {
		return false;
	}
}

?>

<!doctype html>
<html>
	<head>
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
				/*align-items: center;*/
				justify-content: flex-start;
				height: 100vh;
				white-space: nowrap;
			}

			.container {
				margin: 45vh 200px;
			}

			.cpu div {
				display: inline-block;
				border: 2px solid #000;
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

			.command {
				width: 30vw;
			}

			.command p {
				color: #ccc;
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
		<div class="container">
			<a href="#" id="realistic">Toggle realistic scale</a>
			<div class="cpu">
				<?php foreach($events as $event): ?>
					<div 
						<?php if(empty($_GET['realistic']) && $event['process'] != 'null'):?> 
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
