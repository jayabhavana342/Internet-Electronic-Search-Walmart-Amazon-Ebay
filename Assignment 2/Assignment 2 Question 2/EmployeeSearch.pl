#!/usr/bin/perl
require "common.cgi";

sub  query_job 
{
  local( $dept_name,$dept_no,$sal1,$sal2,$comm1,$comm2, $comfile);
  $dept_name = "$contents{dept_name}";
  $dept_no = "$contents{dept_no}";
  $sal1 = "$contents{sal1}";
  $sal2 = "$contents{sal2}";
  $comm1 = "$contents{comm1}";
  $comm2 = "$contents{comm2}";
  $comfile = "/home/Students/j_k201/public_html/demo/proc/unix-version/c++ \"$option\" \"$value\"";
	$proc_returns = `$comfile`;
	return $proc_returns;
}

sub print_job
{
	print "<table border=1>\n";
	print "$query_return";
	print "</table>";
}

&get_parameters( *contents );
$query_return = &query_job;

print "Content-Type: text/html \n\n";
&print_header;

print "<center><h3>Employee Details based on search</h3></center>\n";
&print_job($query_return);

