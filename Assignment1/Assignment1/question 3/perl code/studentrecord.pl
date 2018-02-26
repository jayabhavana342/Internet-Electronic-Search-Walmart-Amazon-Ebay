#!usr/local/bin/perl
print "Enter the path of the file:";
$f=<STDIN>;
open (DATA, $f) or die "File $f cannot be opened \n";
while ($data = <DATA>) 
{
     if (! ($data =~ /^ *$/)) 
     {
         $data =~ s/\s+$//;
         $data =~ s/^\s+//;
         @dataentity = split /\s+/, $data;
	 $entityType = pop(@dataentity);
         $eID    = pop(@dataentity);
         print "emailID of student is:$eID\t";
         $id = pop(@dataentity);
         $lastname = shift(@dataentity);
         if (index($lastname, ",") != -1) 
         {
		chop ($lastname);
         }
	 $userID = $lastname;
         foreach $instance (@dataentity) 
         {
		$userID = $userID . "_" . $instance;
         }
         print "userID of student is:$userID\n";
     }
 }

close(DATA);
