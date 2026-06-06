<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paut'n</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <style>
        dialog {
            border: none;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 600px;
        }
        dialog::backdrop {
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
            backdrop-filter: blur(4px);            /* Blurs the background content */
        }
    </style>
    <div class="container">
        <?php if($content){ echo $content; } ?>
    </div>
</body>
</html>