<?php
print 'SuperAdmin: ' . password_hash('letGSAin', PASSWORD_DEFAULT) . "\n";
print 'Admin: ' . password_hash('letADMin', PASSWORD_DEFAULT) . "\n";
print 'Manager: ' . password_hash('letMANin', PASSWORD_DEFAULT) . "\n";
?>