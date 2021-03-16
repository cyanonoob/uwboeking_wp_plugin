<?php if ($slider) { ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flickity/2.2.2/flickity.pkgd.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flickity/2.2.2/flickity.min.css">
<?php } ?>

<?php if ($css) {
include plugin_dir_path( __FILE__ ) . 'inline-css/single.css.php';
} ?>

<style>
<?php echo $custom_css; ?>
</style>

<?php
$van = isset($_GET['begin']) ? $_GET['begin'] : false;
$tot = isset($_GET['eind']) ? $_GET['eind'] : false;
$pers = isset($_GET['personen']) ? $_GET['personen'] : false;
$params = '';
$params .= $van ? '&per_van='.strtotime($van) : '';
$params .= $tot ? '&per_van='.strtotime($tot) : '';
$params .= $pers ? '&personen='.$pers : '';

$bparams = '';
$bparams .= $van ? '?begin='.$van : '';
$bparams .= $tot ? '&eind='.$tot : '';
$bparams .= $pers ? '&personen='.$pers : '';

$desc = $acc->omschrijving;
$intro = substr($desc,0, strpos($desc, "</p>")+4);
$desc = str_replace($intro, '', $desc);
?>
<div class="buw_single">
	<script>
		var wideThresh = 1000;
		jQuery(document).ready(resize_single);
		jQuery(window).resize(resize_single);
		function resize_single() {
			if (jQuery('.buw_single').width() > wideThresh) {
				jQuery('.buw_single').addClass('wide');
			}
		}
		window.addEventListener('message', e => {
			jQuery('#boekframe').css('height', e.data);
		}, false);
	</script>
	<h2 class="title"><?php echo $acc->naam; ?></h2>
	<div class="intro"><?php echo $intro; ?></div>
	<?php 
	$fotos = $acc->fotos[0];
	if(count($fotos)) { ?>
	<div class="photos">
		<div class="slider_primary js-flickity" data-flickity-options='{ "cellAlign": "left", "contain": true }'>
			<?php foreach($fotos as $foto) { ?>
				<div class="gallery-cell">
					<img src="<?php echo $foto->link; ?>" alt="<?php echo $foto->title; ?>">
				</div>
			<?php } ?>
		</div>
	</div>
	<?php } ?>
	<iframe src="https://www.uwboeking.com/class/class.getModule.php?userModuleID=<?php echo $acc->userModuleID; ?>&userItem=<?php echo $_GET['acc_id']; ?>&lang=<?php echo $lang.$params; ?>" id="boekframe"></iframe>
	<div class="info">
		<span class="description"><?php echo $desc; ?></span>
		<span class="price"><?php echo $acc->prijs_vanaf;?> &euro; <?php echo $acc->prijs; ?></span>
		<a href="<?php echo $arch_url.'/'.$bparams;?>" class="back">Terug naar overzicht</a>
	</div>

</div>