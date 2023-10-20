<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="HTML, CSS, JavaScript">
    <meta name="author" content="John Doe">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete and restore</title>
    <style>
        div {

            border: 1px solid;
            width: 40%;
            height: 300px;
            margin: 200px auto;
        }
        button {
            margin: 130px 10px;
            width: 40%;
            height: 40px;
        }
        button:first-child:hover{
            cursor: pointer;
            background: #EFC507;
            border: none;
        }
        button:last-child:hover{
            cursor: pointer;
            background: #2655FF;
            border: none;
        }
        form{
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
<div>
    <form method="post" action="./settings.php">
        <button type="submit" name="delete">delete</button>
        <button type="submit" name="restore">restore</button>
    </form>
</div>
<?php
set_time_limit(1000);
if(isset($_POST['delete'])){
    if (!is_dir ('../../backup')) {
        mkdir ( '../../backup', 0777, true );
    }
    if(file_exists('../../backup/dashboard.zip')){
        unlink('../../backup/dashboard.zip');
        function backup_mysql_database($options){
            $mtables = array(); $contents = "-- Database: `".$options['db_to_backup']."` --\n";
            $mysqli = new mysqli($options['db_host'], $options['db_uname'], $options['db_password'], $options['db_to_backup']);
            if ($mysqli->connect_error) {
                die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
            }
            $results = $mysqli->query("SHOW TABLES");
            while($row = $results->fetch_array()){
                if (!in_array($row[0], $options['db_exclude_tables'])){
                    $mtables[] = $row[0];
                }
            }
            foreach($mtables as $table){
                $contents .= "-- Table `".$table."` --\n";
                $results = $mysqli->query("SHOW CREATE TABLE ".$table);
                while($row = $results->fetch_array()){
                    $contents .= $row[1].";\n\n";
                }
                $results = $mysqli->query("SELECT * FROM ".$table);
                $row_count = $results->num_rows;
                $fields = $results->fetch_fields();
                $fields_count = count($fields);
                $insert_head = "INSERT INTO `".$table."` (";
                for($i=0; $i < $fields_count; $i++){
                    $insert_head  .= "`".$fields[$i]->name."`";
                    if($i < $fields_count-1){
                        $insert_head  .= ', ';
                    }
                }
                $insert_head .=  ")";
                $insert_head .= " VALUES\n";
                if ($row_count > 0) {
                    $r = 0;
                    while ($row = $results->fetch_array()) {
                        if (($r % 400) == 0) {
                            $contents .= $insert_head;
                        }
                        $contents .= "(";
                        for ($i = 0; $i < $fields_count; $i++) {
                            if ($row[$i] === null) {
                                $contents .= 'NULL';
                            } else {
                                $row_content = str_replace("\n", "\\n", $mysqli->real_escape_string($row[$i]));
                                if ($row_content === "") {
                                    $contents .= 'NULL';
                                } else {
                                    switch ($fields[$i]->type) {
                                        case 8:
                                        case 3:
                                            $contents .= $row_content;
                                            break;
                                        default:
                                            $contents .= "'" . $row_content . "'";
                                    }
                                }
                            }
                            if ($i < $fields_count - 1) {
                                $contents .= ', ';
                            }

                        }
                        if (($r + 1) == $row_count || ($r % 400) == 399) {
                            $contents .= ");\n\n";
                        } else {
                            $contents .= "),\n";
                        }
                        $r++;
                    }
                }
            }
            $backup_file_name = $options['db_to_backup'] . ".sql";
            $fp = fopen($options['db_backup_path'] . '/' . $backup_file_name ,'w+');
            if (($result = fwrite($fp, $contents))) {
                echo "Backup file created '--$backup_file_name' ($result)";
            }
            fclose($fp);
            return $backup_file_name;
        }
        $options = array(
            'db_host'=> 'localhost',  //mysql host
            'db_uname' => 'root',  //user
            'db_password' => '', //pass
            'db_to_backup' => 'store', //database name
            'db_backup_path' => '../../dashboard/web/', //where to backup
            'db_exclude_tables' => array() //tables to exclude
        );
        $backup_file_name=backup_mysql_database($options);
        $folderToZip = '../../dashboard';
        $zipFileName = '../../backup/dashboard.zip';
        $zip = new ZipArchive();
        function addFolderToZip($folder, $zip) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder));
            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($folder) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
        }
        if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
//        $basePath = basename($folderToZip);
            addFolderToZip($folderToZip, $zip);
            $zip->close();

            echo "Folder dk has been successfully zipped to dk.zip.";
        } else {
            echo "Unable to create the ZIP file.";
        }
        $files = scandir('../../dashboard');
        foreach ($files as $value) {
            if ($value === '.' || $value === '..') {
                continue;
            }else{
                if (is_dir('../../dashboard/' . $value)) {
                    if ($value === 'web') {
                        $folders = scandir('../../dashboard/web');
                        foreach ($folders as $val) {
                            if ($val === '.' || $val === '..') {
                                continue;
                            }else{
                                if ($val === 'settings.php') {
                                    continue;
                                } elseif (is_dir('../../dashboard/web/' . $val)) {
                                    removeDir('../../dashboard/web/' . $val);
                                } elseif (is_file('../../dashboard/web/' . $val)) {
                                    unlink('../../dashboard/web/' . $val);
                                }
                            }
                        }
                    }
                    else{
                        removeDir('../../dashboard/' . $value);
                    }
                }
                else{
                    unlink('../../dashboard/' . $value);
                }
            }
        }
        $conn = new mysqli('localhost', 'root', '', 'store');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $tablesToDelete = ['category', 'config', 'orders','order_items','payment','product','store','target'];
        foreach ($tablesToDelete as $table) {
            $sql = "DROP TABLE IF EXISTS $table";
            if ($conn->query($sql) === TRUE) {
                echo "Tables deleted successfully.<br>";
            } else {
                echo "Error deleting tables";
            }
        }
        $conn->close();
    }else{
        function backup_mysql_database($options){
            $mtables = array(); $contents = "-- Database: `".$options['db_to_backup']."` --\n";
            $mysqli = new mysqli($options['db_host'], $options['db_uname'], $options['db_password'], $options['db_to_backup']);
            if ($mysqli->connect_error) {
                die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
            }
            $results = $mysqli->query("SHOW TABLES");
            while($row = $results->fetch_array()){
                if (!in_array($row[0], $options['db_exclude_tables'])){
                    $mtables[] = $row[0];
                }
            }
            foreach($mtables as $table){
                $contents .= "-- Table `".$table."` --\n";
                $results = $mysqli->query("SHOW CREATE TABLE ".$table);
                while($row = $results->fetch_array()){
                    $contents .= $row[1].";\n\n";
                }
                $results = $mysqli->query("SELECT * FROM ".$table);
                $row_count = $results->num_rows;
                $fields = $results->fetch_fields();
                $fields_count = count($fields);
                $insert_head = "INSERT INTO `".$table."` (";
                for($i=0; $i < $fields_count; $i++){
                    $insert_head  .= "`".$fields[$i]->name."`";
                    if($i < $fields_count-1){
                        $insert_head  .= ', ';
                    }
                }
                $insert_head .=  ")";
                $insert_head .= " VALUES\n";
                if ($row_count > 0) {
                    $r = 0;
                    while ($row = $results->fetch_array()) {
                        if (($r % 400) == 0) {
                            $contents .= $insert_head;
                        }
                        $contents .= "(";
                        for ($i = 0; $i < $fields_count; $i++) {
                            if ($row[$i] === null) {
                                $contents .= 'NULL';
                            } else {
                                $row_content = str_replace("\n", "\\n", $mysqli->real_escape_string($row[$i]));
                                if ($row_content === "") {
                                    $contents .= 'NULL';
                                } else {
                                    switch ($fields[$i]->type) {
                                        case 8:
                                        case 3:
                                            $contents .= $row_content;
                                            break;
                                        default:
                                            $contents .= "'" . $row_content . "'";
                                    }
                                }
                            }
                            if ($i < $fields_count - 1) {
                                $contents .= ', ';
                            }

                        }
                        if (($r + 1) == $row_count || ($r % 400) == 399) {
                            $contents .= ");\n\n";
                        } else {
                            $contents .= "),\n";
                        }
                        $r++;
                    }
                }
            }
            $backup_file_name = $options['db_to_backup'] . ".sql";
            $fp = fopen($options['db_backup_path'] . '/' . $backup_file_name ,'w+');
            if (($result = fwrite($fp, $contents))) {
                echo "Backup file created '--$backup_file_name' ($result)";
            }
            fclose($fp);
            return $backup_file_name;
        }
        $options = array(
            'db_host'=> 'localhost',  //mysql host
            'db_uname' => 'root',  //user
            'db_password' => '', //pass
            'db_to_backup' => 'store', //database name
            'db_backup_path' => '../../dashboard/web/', //where to backup
            'db_exclude_tables' => array() //tables to exclude
        );
        $backup_file_name=backup_mysql_database($options);
        $folderToZip = '../../dashboard';
        $zipFileName = '../../backup/dashboard.zip';
        $zip = new ZipArchive();
        function addFolderToZip($folder, $zip) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder));
            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($folder) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
        }
        if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
//        $basePath = basename($folderToZip);
            addFolderToZip($folderToZip, $zip);
            $zip->close();

            echo "Folder dk has been successfully zipped to dk.zip.";
        } else {
            echo "Unable to create the ZIP file.";
        }
        $files = scandir('../../dashboard');
        foreach ($files as $value) {
            if ($value === '.' || $value === '..') {
                continue;
            }else{
                if (is_dir('../../dashboard/' . $value)) {
                    if ($value === 'web') {
                        $folders = scandir('../../dashboard/web');
                        foreach ($folders as $val) {
                            if ($val === '.' || $val === '..') {
                                continue;
                            }else{
                                if ($val === 'settings.php') {
                                    continue;
                                } elseif (is_dir('../../dashboard/web/' . $val)) {
                                    removeDir('../../dashboard/web/' . $val);
                                } elseif (is_file('../../dashboard/web/' . $val)) {
                                    unlink('../../dashboard/web/' . $val);
                                }
                            }
                        }
                    }
                    else{
                        removeDir('../../dashboard/' . $value);
                    }
                }
                else{
                    unlink('../../dashboard/' . $value);
                }
            }
        }
        $conn = new mysqli('localhost', 'root', '', 'store');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $tablesToDelete = ['category', 'config', 'orders','order_items','payment','product','store','target'];
        foreach ($tablesToDelete as $table) {
            $sql = "DROP TABLE IF EXISTS $table";
            if ($conn->query($sql) === TRUE) {
                echo "Tables deleted successfully.<br>";
            } else {
                echo "Error deleting tables";
            }
        }
        $conn->close();
    }


}else if(isset($_POST['restore'])){
    if(file_exists('../../dashboard/.htaccess')){
        echo 'The file exists.';
    }else{
        $zip = new ZipArchive;
        $zipFile = '../../backup/dashboard.zip';
        $res = $zip->open($zipFile, ZipArchive::CREATE);
        if ($res === true)
        {
            $path = '../../dashboard';
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $fileInfo = $zip->statIndex($i);
                $fileName = $fileInfo['name'];
                $zip->extractTo($path, $fileName);
            }
            $zip->close();
        }
        function copyDirectory($source, $destination) {
            if (is_dir($source)) {
                if (!is_dir($destination)) {
                    mkdir($destination, 0777, true);
                }
                $files = scandir($source);
                foreach ($files as $file) {
                    if ($file != '.' && $file != '..') {
                        $src = $source . '/' . $file;
                        $dst = $destination . '/' . $file;
                        if ($file === 'web') {
                            if (!is_dir($dst)) {
                                mkdir($dst, 0777, true);
                            }
                            copyDirectory($src, $dst);
                        } else {
                            if ($file !== 'settings.php') { // Exclude settings.php here
                                if (is_dir($src)) {
                                    copyDirectory($src, $dst);
                                } else {
                                    copy($src, $dst);
                                }
                            }
                        }
                    }
                }
            } else {
                copy($source, $destination);
            }
        }
        $sourceDirectory = '../../dashboard/ns/dashboard';
        $destinationDirectory = '../../dashboard';
        copyDirectory($sourceDirectory, $destinationDirectory);
        removeDir('../../dashboard/ns');

        $filename = '../../dashboard/web/store.sql';
        $sql = file_get_contents($filename); // Corrected the file_get_contents parameter.
        $mysqli = new mysqli("localhost", "root", "", "store");
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }
        $templine = '';
        $lines = explode(";", $sql); // Split the SQL file into individual SQL statements.
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line == '') {
                continue;
            }
        }
        unlink('../../dashboard/web/store.sql');
    }

}
?>
<?php
function removeDir($path){
    // echo $path.'<hr>';
    exec(sprintf("rd /s /q %s", escapeshellarg($path)));
}
?>
</body>
</html>