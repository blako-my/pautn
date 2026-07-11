<?php
ob_start();
include('function.php');
?>
<dialog id="userdialog"></dialog>
<?php
if(isset($_SESSION['userid']))
{
    if(isset($_GET['file']))
    {
        ?>
        <a href="?deletedir=<?php echo($_SESSION['userid']);?>" class="btn btn-danger float-right" onclick="return confirm('Are you sure you want to delete this directory?')">Padam Direktori</a>
        <?php
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
            if(updateservice($conn,$_SESSION['userid'],$serviceadd))
            {
                if (!mkdir($upload_dir, 0755, true)) {
                    echo('Failed to create directories...');
                }
                else
                {
                    header("Location:user.php?file");
                }
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
    else if(isset($_GET['link']))
    {
        $file_dir = 'data/'.$_SESSION['userid'].'.user';
        if(file_exists($file_dir))
        {
            $data = json_decode(file_get_contents($file_dir),true);
            if (json_last_error() === JSON_ERROR_NONE) {
                ?><textarea name="" id="" rowS="10" cols="50"><?php echo(file_get_contents($file_dir));?></textarea><?php
                print_r($data);
            } else {
                echo "Error: Invalid JSON format. " . json_last_error_msg();
            }
        }
        else
        {
            $data = [
                "profile"=>["username"=>$_SESSION['username']]
            ];
            $serviceadd = [
                "pautn" => [
                    "tier" => "free"
                ]
            ];
            if(updateservice($conn,$_SESSION['userid'],$serviceadd))
            {
                file_put_contents($file_dir, json_encode($data,JSON_PRETTY_PRINT));
            }
            header("Location:?link");
        }
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
            deletedirectory($dirpath,'?');
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