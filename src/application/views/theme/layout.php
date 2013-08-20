<!DOCTYPE html> 
<html> 
<head> 
	<title>Rdv Online</title> 
	
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	
	
	<meta name="viewport" content="width=device-width, initial-scale=1" /> 
	
<?php if(!isset($njqm)||!$njqm) : ?>	
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" />
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>

	<script >
$(document).bind("mobileinit", function(){
	$.mobile.defaultPageTransition = 'none';
	$.mobile.defaultDialogTransition = 'none';
	$.mobile.useFastClick = true;
	
	//apply overrides here
	$.mobile.ajaxLinksEnabled = false;
	$.mobile.ajaxFormsEnabled = false;
	$.mobile.ajaxEnabled = false;
	//$.mobile.defaultTransition = false;
	//$.mobile.loadingMessage = "loading3";
	//$.mobile.pushStateEnabled = true;
	
	
});

</script>
	
	<script src="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.js"></script>
<?php endif; ?>






	<style>
		.label {
			font-style:italic;
		}
		.synopsis {
			text-align:center;
			font-weight:bold;
			padding-bottom:10px;
			border-bottom:1px dotted grey;
		}
		.separator {
			border-bottom:1px dotted grey;
			margin-bottom:10px;
		}
		
		<?php // test if media queries are desactivated
		if (!isset($mq)||$mq) : ?>
			@media screen and (min-width: 600px) {
				.page_content {
					padding:0 20% 0 20%;
				}
				
				/*.ui-btn {
					width: 70%;
					margin-left:auto;
					margin-right:auto;
				}*/
				
				.label {
					position:relative;
					bottom:4px;
				}
							
				.header_button {
					padding:1%;
				}
			}
		<?php endif; ?>
	</style>

	
	
	
	
</head> 
	<body> 
		<div data-role="page">

			<?php 
			// test if we want to show header
			if (!isset($s_header)||$s_header): ?>

				<header data-role="header" data-position="fixed">
					<?php
						// left button
						if (isset($header_url_back)) {
							echo '<a class="header_button" 
									href="'.$header_url_back.'"
									data-icon="back"
									data-iconpos="notext"
									>Retour</a>';
						}
					?>
					
					
					<div style="padding: 2%;text-align:center;">
						<?php
							if (isset($header_title)) {
								echo $header_title;
							} 
						?>
					</div>
					
					<?php
						// right button
						if (isset($header_url_home)) {
							echo '<a class="header_button
											ui-btn-right" 
									href="'.$header_url_home.'"
									data-icon="home"
									data-iconpos="notext"
									>Accueil</a>';
						} else if (isset($header_url_disconnect)) {
							echo '<a class="header_button
											ui-btn-right"
									href="'.$header_url_disconnect.'"
									data-icon="delete"
									data-iconpos="notext"
									>Déconnexion</a>';
						}
					?>
					
					
				</header><!-- /header -->

			<?php endif;?>

			<div class="page_content" data-role="content">	
				<?php echo $page ?>
			</div>
				
		</div><!-- /page -->
	</body>
</html>
