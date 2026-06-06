<?php
ob_start();
include('function.php');
?>
<dialog id="userdialog"></dialog>
<?php
if(isset($_SESSION['userid']))
{
    ?>
    <a class="btn btn-info" href="?">Utama</a>
    <a class="btn btn-danger float-right" href="?logout">Log Keluar</a>
    <br>
    <?php
    echo "<h1>Selamat Datang, ".$_SESSION['username']."</h1>";
    if(isset($_GET['file']))
    {
        $upload_dir = 'uploads/'.$_SESSION['userid'];
        if(file_exists($upload_dir))
        {
            $files = array_diff(scandir($upload_dir), array('..', '.'));
            if(count($files) < 10)
            {
            ?>
            <h1>Muat Naik Gambar</h1>
            <form action="user.php" method="post" enctype="multipart/form-data">
                <input type="file" class="form-control" name="user_fileupload" id="user_fileupload" accept="image/*" required><br>
                <img src="" alt="" id="user_filepreview" style="height: 200px; max-width: 100%; object-fit: contain;"><br>
                <input type="submit" class="btn btn-info" name="user_uploadfile" value="Muat Naik">
                <button class="btn btn-secondary" onclick="document.getElementById('user_fileupload').value = '';document.getElementById('user_filepreview').src = '';">Reset</button>
            </form>
            <?php
            }
            else
            {
                echo "<h1>Maaf, anda telah mencapai had muat naik.</h1>";
            }

        }
        else
        {
            $serviceadd = [
                "fileupload" => [
                    "tier" => "free",
                    "maxfiles" => 10
                ]
            ];
            $json_serviceadd = json_encode($serviceadd);
            $stmt = $conn->prepare("UPDATE T1_user SET T1_service = ? WHERE T1_id = ?");
            $stmt->bind_param("ss", $json_serviceadd, $_SESSION['userid']);
            if ($stmt->execute())
            {
                $stmt->close();
                echo "Service Updated";
                if (!mkdir($upload_dir, 0755, true)) {
                    echo('Failed to create directories...');
                }
                else
                {
                    echo "Directory Created";
                }
                header("Location: user.php?file");
                exit();
            }
        }
        $files = array_diff(scandir($upload_dir), array('..', '.'));
        ?>
        <h3 class=""><?php echo count($files)." file(s) found.";?></h3>
        <div class="row row-cols-5 g-4" id="cardContainer">
        <?php
        foreach($files as $file)
        {
            ?>
            <div class="col">
                <div class="card h-100">
                    <img src="<?php echo $upload_dir.'/'.$file;?>" class="card-img-top" alt="<?php echo $file;?>" style="height: 200px; max-width: 100%; object-fit: contain;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $file;?></h5>
                        <a href="?delete=<?php echo $file;?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this file?')">Delete</a>
                        <a href="<?php echo $upload_dir.'/'.$file;?>" class="btn btn-primary" download>Download</a>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        </div>
        <?php
    }
    else if(isset($_GET['delete']))
    {
        if(!empty($_GET['delete']))
        {
            $filepath = 'uploads/'.$_SESSION['userid'].'/'.$_GET['delete'];
            deletefile($filepath,'?file');
        }
    }
    else if(isset($_GET['deletedir']))
    {
        if(!empty($_GET['deletedir']))
        {
            $dirpath = 'uploads/'.$_SESSION['userid'];
            rmdir('uploads/'.$_SESSION['userid']);
            header("Location: user.php?");
        }
    }
    else
    {
        ?>
            <a href="?file" class="btn btn-info">Lihat Fail</a>
        <?php
        if(file_exists('uploads/'.$_SESSION['userid']))
        {
            ?>
            <a href="?deletedir=<?php echo($_SESSION['userid']);?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this directory?')">Padam Direktori</a>
            <?php
        }
    }
}
else
{
    if(isset($_GET['login']))
    {
        if(!empty($_GET['login']))
        {
            echo "<script>alert('".$_GET['login']." telah berjaya didaftarkan. Sila log masuk untuk meneruskan.');</script>";
        }
        ?>
        <script>
            const loginform = `
                <form action="user.php" method="post">
                <h1>Log Masuk</h1>
                <input type="text" name="login_name" class="form-control" placeholder="Nama Pengguna" required><br>
                <input type="password" name="login_password" class="form-control" placeholder="Kata Laluan" required><br>
                <input type="submit" name="login_true" class="btn btn-primary" value="Log Masuk">
                </form>
                <a href="?register">Belum ada akaun? Daftar di sini.</a>
            `;
            opendialog(document.getElementById('userdialog'),loginform);
        </script>
        <?php
    }
    else if(isset($_GET['register']))
    {
        ?>
        <script>
            const registerform = `
                <form action="user.php" method="post">
                <h1>Daftar Akaun</h1>
                <input type="text" name="register_name" class="form-control" placeholder="Nama Pengguna" required><br>
                <input type="password" name="register_password" class="form-control" placeholder="Kata Laluan" required><br>
                <input type="password" name="register_confirm_password" class="form-control" placeholder="Sahkan Kata Laluan" required><br>
                <input type="email" name="register_email" class="form-control" placeholder="Emel" required><br>
                <input type="submit" name="register_true" class="btn btn-primary" value="Daftar">
                </form>
                <a href="?login">Sudah ada akaun? Log masuk di sini.</a>
            `;
            opendialog(document.getElementById('userdialog'),registerform);
        </script>
        <?php
    }
    else
    {
        header("Location: user.php?login");
        exit();
    }
}
$content = ob_get_clean();
?>

<?php include 'views/layout-master.php';?>