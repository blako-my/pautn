test
pautn.php
<?php
$json = file_get_contents('api/userid/get.php?username=danialproperty');
$data = json_decode($json);
echo $data;
?><script>
    passusername = '954d6ce976c35e1c';
</script><?php
include('index.html');
?>