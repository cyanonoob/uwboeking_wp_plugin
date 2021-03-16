<?php if ($css) {
include plugin_dir_path( __FILE__ ) . 'inline-css/search.css.php';
} ?>

<?php echo $custom_css; ?>

<form action="<?php echo $arch_url; ?>" class="buw_search">
    <?php 
    $now = new DateTime();
    $tomorrow = new DateTime();
    $tomorrow = $tomorrow->add(new DateInterval('P1D'));
    ?>
    <div class="dates">
        <span class="from">
            <label for="date-start">Van</label>
            <input id="date-start" type="date" name="begin" placeholder="Selecteer een datum" min="<? echo $now->format("Y-m-d"); ?>" required>
        </span>
        <span class="until">
            <label for="date-end">Tot</label>
            <input type="date" id="date-end" name="eind" placeholder="Selecteer een datum" min="<? echo $tomorrow->format("Y-m-d"); ?>" required>
        </span>
    </div>
    <div class="persons">
        <label for="date-persons">Aantal personen</label>
        <select id="date-persons" name="personen" placeholder="Aantal personen" required>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="6">5</option>
            <option value="5">6</option>
        </select>
    </div>
    <input type="submit" class="button primary" value="Beschikbaarheid bekijken">
</form>