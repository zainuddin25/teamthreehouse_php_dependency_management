<?php 
if (!empty($_GET['e'])) {
    $timestamp = filter_input(INPUT_GET,'e',FILTER_SANITIZE_NUMBER_INT);
    $entry = 'logs/'. $timestamp .'.txt';
    if (file_exists($entry)) {
        $file = file_get_contents($entry);
    }
}
if (empty($file)) {
    header('location: index.php');
}

include("inc/header.php"); ?>
                <div class="entry-list single">
                    <article>
                        <h1><?php
                            $d = new DateTime();
                            $d->setTimezone(new DateTimeZone('America/Los_Angeles'));
                            $d->setTimestamp($timestamp);
                            //echo '<time datetime="' . $d->format('Y-m-d h:i:s') . '">';
                            echo $d->format('l jS \of F Y \<\b\r \/\>\@ h:i:s A');
                            //echo '</time>';
                        ?></h1>
                        <div class="entry">
                            <?php echo $file; ?>
                        </div>
                    </article>
                </div>
<?php include("inc/footer.php"); ?>