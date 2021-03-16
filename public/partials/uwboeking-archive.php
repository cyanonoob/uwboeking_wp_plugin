<?php if ($css) {
include plugin_dir_path( __FILE__ ) . 'inline-css/archive.css.php';
} ?>

<style>
<?php echo $custom_css; ?>
</style>

<div class="buw_archief">
<h1>Onze accomodaties</h1>
<?php
$van = isset($_GET['begin']) ? $_GET['begin'] : false;
$tot = isset($_GET['eind']) ? $_GET['eind'] : false;
$pers = isset($_GET['personen']) ? $_GET['personen'] : false;
$params = '';
$params .= $van ? '&begin='.$van : '';
$params .= $tot ? '&eind='.$tot : '';
$params .= $pers ? '&personen='.$pers : '';
foreach($accs as $acc) {
    $excerpt = substr($acc->omschrijving,0, strpos($acc->omschrijving, "</p>")+4);
    ?>
    <article>
        <img src="<?php echo $acc->afbeelding; ?>" alt="<?php echo $acc->naam; ?>">
        <h3 class="title"><?php echo $acc->naam; ?></h3>
        <div class="excerpt"><?php echo strip_tags($excerpt); ?></div>
        <div class="price_book">
            <span class="price"><?php echo $acc->prijs_vanaf;?> &euro; <?php echo $acc->prijs; ?></span>
            <a href="?acc_id=<?php echo $acc->houseID . $params; ?>" class="book_now">Boeken</a>
        </div>
    </article>
    <?php
}
?>
</div>