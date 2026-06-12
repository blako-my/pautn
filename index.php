<!DOCTYPE html>
<?php
include 'config.php';
$username = $_GET['username'];
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Link Aggregator Page</title>
    <link id="favicon" rel="icon" href="uploads/default-icon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5514264872807416" crossorigin="anonymous"></script>
</head>
<body class="th-bg th-fg">
    <dialog id="dialog"></dialog>
    <div id="container" class="container p-4">
        <div id="error" class="text-danger text-center th-surface rounded mb-2"></div>
        <div id="profile" class="th-fg text-center mt-5"></div>
        <div id="sections" class="th-fg"></div>
    </div>
    <script src="main.js"></script>
    <script>
        fetch('api/username/get.php?username=<?php echo($username);?>')
        .then(response => {
            if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json(); // Parses JSON response into native JS objects
        })
        .then(data => {
            //initpage(data.data.id);
            generatepage(data.data.id);
            //console.log(data.data.name);
        })
        .catch(error => {
            showdialog(document.getElementById('dialog'),error,"error");
            console.error('Error:', error);
        });
    </script>
</body>
</html>