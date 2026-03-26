<?php

########################################################################
# Extension Manager/Repository config file for ext "mr_usrgrpmgmt".
#
# Auto generated 23-08-2010 09:52
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = [
    'title' => 'User Group Management',
    'description' => 'This is a Backend-related extension to manage both Backend and Frontend users from the edit form of Backend/Frontend groups. Easily assign users to groups or remove them from groups.',
    'category' => 'be',
    'version' => '1.4.0-dev',
    'state' => 'stable',
    'author' => 'Xavier Perseguers',
    'author_email' => 'xavier@causal.ch',
    'author_company' => 'Causal Sàrl',
    'constraints' => [
        'depends' => [
            'php' => '8.1.0-8.5.99',
            'typo3' => '12.4.0-14.3.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
