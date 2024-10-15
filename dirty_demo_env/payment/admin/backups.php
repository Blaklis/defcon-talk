<?php

$backups = scandir('backups');
echo "Here are the lists of your backup files";
echo "<ul>";
foreach($backups as $backup){
    if($backup == '.' || $backup == '..')
        continue;
    echo "<li>".$backup." - <a href='restore.php?name=$backup'>Restore</a></li>";
}
echo "</ul>";
echo "Create a new backup file by clicking <a href='backup.php'>here</a>";