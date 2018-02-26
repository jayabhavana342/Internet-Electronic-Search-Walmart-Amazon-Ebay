#!usr/local/perl

print "Enter FilePath :";
$file=<STDIN>;
open (DATA, $file) or die "Invalid File \n";

%logincount;
%usrcount;
%count;

while  ($data = <DATA>)
{
	@arr = split /:/ ,$data;
	
	$usr = $arr[0];
	$login = $arr[@arr-1];
	
	chomp ($login);
	chomp ($usr);
	
	$logincount{$login}++;
	
	if(defined $usrcount{$login})
	{
		$usrcount{$login} = "$usrcount{$login},$usr";
	}
	else
	{
		$usrcount{$login}=$usr;
	}
	
	$count{$login}++;
}

print "(1)The number of users for each kind of login shell\n";

foreach my $str(keys %logincount)
{
	print "$logincount{$str} people use $str as their login shell\n";	
}

print "(2)The names of all people who use the same shell.\n";
foreach my $s(keys %usrcount)
{	
	print "The following $count{$s} people use $s values $usrcount{$s}\n";
}