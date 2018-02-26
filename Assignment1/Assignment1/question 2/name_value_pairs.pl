#!usr/local/perl

print "Enter the expression:";
$expr=<STDIN>;

@name = split /&/, $expr;

foreach $pair (@name) 
{
	$pair =~ s/\s+$//;
	$pair =~ s/^\s+//;
	chomp($pair);
	@value = split /=/, $pair;

	$value[0] =~ s/\s+$//;
	$value[0] =~ s/^\s+//;
	$value[1] =~ s/\s+$//;
	$value[1] =~ s/^\s+//;
	print "$value[0] is $value[1]\n";
}

