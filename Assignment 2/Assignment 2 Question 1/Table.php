<!DOCTYPE html>
<html>
<head>
<title>AIIP ASSIGNMENT</title>
</head>

<body>
<?php

class Table 
{
	var $table_array = array();
	var $headers = array();
	var $cols;
	
	function Table ( $headers ) 
	{
		$this->headers = $headers;
		$this->cols = count ($headers);
	}

	function addRow ( $row ) 
	{
		if ( count ($row) != $this->cols )
			return false;
		array_push ( $this->table_array, $row );
		return true;
	}


	function addRowAssocArray ( $row_assoc ) 
	{
		$row = array();
		foreach ( $this->headers as $header ) 
		{
			if ( ! isset ($row_assoc[$header] ) )
				$row_assoc[$header] = "";
			$row[] = $row_assoc[$header];
		}
		array_push($this->table_array, $row);
		return true;
	}


	function output ( ) 
	{
		print "<pre>";
		foreach ( $this->headers as $header )
		print "<b>$header</b> ";
		print "\n";
		foreach ( $this->table_array as $y ) 
		{
			foreach ( $y as $xcell )
			print "$xcell ";
			print "\n";
		}
		print "</pre>";
	}

	function rmRow ( $row )
    	{
        $key = array_keys($this->table_array, $row);
        	foreach($key as $searchKey)
        	{
        	    unset($this->table_array[$searchKey]);
        	}
        return true;
    	}
	
    	function rmRowAssocArray ( $row_assoc )
    	{
    	    $row = array();
    	    foreach ( $this->headers as $header )
    	    {
    	        if ( ! isset ($row_assoc [$header] ) )
    	            $row_assoc[$header] = "";
    	        $row[] = $row_assoc [$header];
    	    }
    	    $key = array_keys($this->table_array, $row);
	    foreach($key as $newKey)
       	    {
            	unset($this->table_array[$newKey]);
            }
            return true;
    	}

	function addCol($header, $value=NULL )
	{
	        if(isset($header))
		{
        	    $this->headers[] = $header;
        	    $this->cols +=1;
        	}
        	if(!isset($value))
        	    $value = "null";    
        	$index = count($this->table_array);
		for($i = 1; $i <= $index; $i++)
        	    $this->table_array[$i][] = $value;    
    	}

	function rmCol($rm_col)
	{
        	$position = array_search($rm_col, $this->headers);
        	if($position != "")
        	{
		    unset($this->headers[$position]);
        	    foreach($this->table_array as &$x) 
        	    {
			    unset($x[$position]);
                    }
        	}
        	else
        	    return false;
    	}

	function renameCol($old_col,$new_col)
	{    
		$position = array_search($old_col, $this->headers);
		if($position != "")
	        {
			$this->headers[$position]=$new_col;
		}
	        else
			return false;
	}
}

$test = new table ( array ("a", "b", "c") );
$test->addRow ( array (1, 2, 3 ) );
$test->addRow ( array (5, 6, 7 ) );
$test->addRowAssocArray ( array ("b"=>0, "a"=>6, "c"=>3 ) );
$test->addRow ( array (6, 7, 8 ) );
$test->addRow( array(4,5,5));

print "Array is initialized .\n ";
$test->output();

$test->rmRow (array (1, 2, 3 ) );
print"Removing  row (1, 2, 3). \n";
$test->output();


$test->rmRowAssocArray ( array ("b"=>5, "a"=>4, "c"=>5 ) );
print"Removing Assoc Array ( 4,5,5 ). \n";
$test->output();

print "Adding Column 'd' with value '10'.\n";
$test->addCol('d',10);
$test->output();

print "Adding Column 'e' without value.\n";
$test->addCol('e');
$test->output();

print "Table before removing the columns";
print "\n";
$test->output();

print "Table after removing the column 'b'";
$test->rmCol('b');
$test->output();

print "Table after renaming the column.";
$test->renameCol('c','h');
$test->output();
?>

</body>
</html>
