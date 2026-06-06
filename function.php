<?php
session_start();
include('config.php');
date_default_timezone_set('Asia/Kuala_Lumpur');
if(isset($_GET['logout']))
{
    session_unset();
    session_destroy();
    header("Location: user.php");
}
if(isset($_POST['login_true']))
{
    //TODO: Need more security
    $username = $_POST['login_name'];
    $password = $_POST['login_password'];
    $stmt = $conn->prepare("SELECT * FROM T1_user WHERE T1_name = ?");
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $login_r = mysqli_fetch_assoc($stmt->get_result());
    if (password_verify($password, $login_r['T1_passwordhash']))
    {
        $_SESSION['userid'] = $login_r['T1_id'];
        $_SESSION['username'] = $login_r['T1_name'];
    }
}
else if(isset($_POST['register_true']))
{
    $userid = bin2hex(random_bytes(8));
    $username = $_POST['register_name'];
    $password = $_POST['register_password'];
    $confirm_password = $_POST['register_confirm_password'];
    $email = $_POST['register_email'];
    if ($password === $confirm_password)
    {
        $passwordhash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO T1_user (T1_id, T1_name, T1_passwordhash, T1_email) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $userid, $username, $passwordhash, $email);
        if($stmt->execute())
        {
            header("Location: user.php?login=".escape($username));
            exit();
        }
    }
}

function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
function uploadfile($destination,$file)
{
    $target_file = $destination . basename($file["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
    // Check file size
    if ($file["size"] > 10485760) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars( basename( $file["name"])). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
function deletefile($filepath,$redirect = null)
{
    if (file_exists($filepath)) {
        unlink($filepath);
    }
    if ($redirect) {
        header("Location: $redirect");
        exit();
    }
}
if(isset($_POST['user_uploadfile']))
{
    uploadfile('uploads/'.$_SESSION['userid'].'/', $_FILES['user_fileupload']);
    header("Location: user.php?file");
    exit();
}
?>

<script>
    function closedialog(dialog) {
        dialog.replaceChildren();
        dialog.close();
    }
    function opendialog(dialog,content) {
        content = `
        <button class="btn float-right" onclick="closedialog(document.getElementById('userdialog'))">Tutup</button>
        `+content;
        const parsedContent = document.createRange().createContextualFragment(content);
        dialog.replaceChildren(parsedContent);
        dialog.showModal();
    }
</script>
<?php
if(isset($_GET['file']))
{
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const fileInput = document.getElementById('user_fileupload');
            fileInput.addEventListener('change', (event) => {
            // Check if a file was selected
            if (event.target.files.length > 0) {
                const file = event.target.files[0];
                if (!file) return;
                const fileSizeInBytes = file.size;
                const maxSize = 10 * 1024 * 1024; //* Example limit: 10MB (10 * 1024 * 1024 bytes)
                if (fileSizeInBytes > maxSize) {
                    fileInput.setCustomValidity('File size must not exceed 10MB.');// Use setCustomValidity to flag a native HTML5 validation error
                    fileInput.reportValidity();
                } else {
                    fileInput.setCustomValidity('');// Clear the error if the file is valid
                    const fileURL = URL.createObjectURL(file);
                    const preview = document.getElementById('user_filepreview');
                    preview.src = fileURL;
                }
            }
            });
        });
    </script>
    <?php
}
?>