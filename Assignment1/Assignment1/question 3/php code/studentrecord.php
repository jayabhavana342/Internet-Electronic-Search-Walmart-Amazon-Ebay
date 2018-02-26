<?php
 
echo "Enter the file path:";
$f = trim(fgets(STDIN));

$myfile = fopen($f, "r") or die("Invalid File");
$filecontent = fread($myfile,filesize($f));
fclose($myfile);

$record = explode("\n", $filecontent);

foreach ($record as $value) 
{
    if (!empty($value)) 
    {
    $recordentity = preg_split('#\s+#', $value, null, PREG_SPLIT_NO_EMPTY);
    $entityType = array_pop($recordentity);
    $emailID    = array_pop($recordentity);
    echo "EmailID:$emailID\t";
    $id = array_pop($recordentity);
    $lastname = array_shift($recordentity);
    $last = rtrim($lastname, ",");
    $userID = $last;
    foreach ($recordentity as $instance) 
    {
        $userID = $userID . "_" . $instance;
    }
    echo "userID:$userID\n";
    }
}

?>
