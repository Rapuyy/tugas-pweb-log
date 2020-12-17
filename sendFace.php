<?php
include('config.php');
$base64_string = $_POST['image'];
$username = $_POST['username'];
$password = $_POST["password"];
$image_name = "E:\\xampp\\htdocs\\tugas-pweb-log\\uploadFace\\" . $username;
$sql = "select * from admin where username = '$username'";
$query = mysqli_query($db_connection, $sql);
$result = mysqli_fetch_assoc($query);
if (mysqli_num_rows($query) == 0) {
    echo "<p>salah username </p>";
} else {
    if (($password) != $result['password']) {
        echo "<p>salah password </p>";
    } else { //jika username dan password sama dan benar, jalankan..
        if (!file_exists($image_name)) {
            if (!mkdir($image_name)) {
                $m = array('msg' => "REJECTED, cant create folder");
                echo json_encode($m);
                return;
            }
        }
        
        $fi = new FilesystemIterator($image_name, FilesystemIterator::SKIP_DOTS);
        $fileCount = iterator_count($fi) + 1;
        $data = explode(',', $base64_string);
        $fullName = $image_name . "\\X__" . $fileCount . "_" . date("YmdHis") . ".png";
        $ifp = fopen($fullName, "wb");
        fwrite($ifp, base64_decode($data[1]));
        fclose($ifp);
        if (!$ifp) {
            $m = array('msg' => "REJECTED, " . $fullName . "not saved");
            echo json_encode($m);
            return;
        }
        
        // $command = escapeshellcmd("python checkFace.py ".$fullName);
        // $output = shell_exec($command);
        
        $fi = new FilesystemIterator($image_name, FilesystemIterator::SKIP_DOTS);
        $fileCount = iterator_count($fi);
        $m = array('msg' => "Berhasil Mengirim" . " total(" . $fileCount . ")");
        echo json_encode($m);
        $sql = "insert into log (username) values('$username')";
        $query = mysqli_query($db_connection, $sql);
    }
}




