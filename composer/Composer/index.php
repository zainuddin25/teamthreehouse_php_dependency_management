<?php include("inc/header.php"); ?>
                <div class="entry-list">
                    <h1>Email History</h1>
                    <?php
                    if ($emails = array_diff(scandir('logs',SCANDIR_SORT_DESCENDING), array('.', '..'))) {
                        foreach ($emails as $entry) {
                            $timestamp = substr($entry,0, -4);
                            echo "<article>\n";
                            echo "<h2><a href='detail.php?e=" . $timestamp . "'>";
                            $file = fopen('logs/' . $entry, 'r');
                            echo fgets($file);
                            fclose($fille);
                            echo "</a></h2>";
                            $d = new DateTime();
                            $d->setTimezone(new DateTimeZone('America/Los_Angeles'));
                            $d->setTimestamp($timestamp);
                            echo "<time>" . $d->format('l jS \of F Y \<\b\r \/\>\@ h:i:s A') . "</time>\n";
                            //echo file_get_contents('logs/'.$entry);
                            echo "</article>\n";
                        }
                    } else {
                        echo '<article><h2>Logs are empty</h2></article>';
                    }
                    ?>
                </div>
<?php include("inc/footer.php"); ?>