<html>
<head>
<title>ASSIGNMENT 2</title>
</head>

<body>
<h1>Employee Details Search</h1>

<?php

//create short variable names
$dept_name = $_POST['dept_name'];
$dept_no = $_POST['dept_no'];
$sal1 = $_POST['sal1'];
$sal2 = $_POST['sal2'];
$comm1 = $_POST['comm1'];
$comm2 = $_POST['comm2'];

if(!$dept_name || $dept_no || $sal1 || $sal2 || $comm1 || $comm2)
{
echo 'You have not entered search details please try again';
exit;
}

if(!get_magic_quotes_gpc())
{
$dept_name = addslashes($dept_name);
$dept_no = addslashes($dept_no);
$sal1 = addslashes($sal1);
$sal2 = addslashes($sal2);
$comm1 = addslashes($comm1);
$comm2 = addslashes($comm2);
}

@ $db = new mysqli('localhost','J_K201@CSDBORA','****','J_K201');

if(mysqli_connect_errno())
{
echo 'Error could not connect to database';
exit;
}

if($dept_no)
$query = "select ename,sal,comm from emp e,dept d where d.deptno = ".$dept_no"";

if($dept_name)
$query = "select ename,sal,comm from emp e,dept d where d.dname = ".$dept_name" and d.deptno = e.deptno;

if($sal1 && $sal2)
$query = "select e.ename,e.sal,e.comm from emp e,dept d where e.sal>sal1 and e.sal<sal2;

if($comm1 && $comm2)
$query = "select e.ename,e.sal,d.dname from emp e,dept d where e.sal>e.comm1 and e.sal<e.comm2;

$result = $db->query($query);

$num_results = $result_rows;

echo "<p>Results found</p>";

for($i=0;$i<num_results;$i++)
{

$row = $result->fetch_assoc();
echo "<center>";
echo "<table border = 1>";
echo stripslashes($row);
echo "<br />";
}

$result->free();
$db->close();

?>
</body>
</html>
