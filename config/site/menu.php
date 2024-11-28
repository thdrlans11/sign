<?

return [
    'menu' => [
        '1' => [
            'name' => 'About the Congress',
            'url' => "",
            'route_target' => 'about.overview',
            'route_param' => [],
        ],
        '2' => [
            'name' => 'Program',
            'url' => "",
            'route_target' => 'ready',
            'route_param' => ['mainNum'=>'2', 'subNum'=>'1'],
        ],
        '3' => [
            'name' => 'Abstract',
            'url' => "",
            'route_target' => 'ready',
            'route_param' => ['mainNum'=>'3', 'subNum'=>'1'],
        ],
        '4' => [
            'name' => 'Registration',
            'url' => "",
            'route_target' => 'registration.guide',
            'route_param' => [],
        ],
        '6' => [
            'name' => 'Sponsors',
            'url' => "",
            'route_target' => 'ready',
            'route_param' => ['mainNum'=>'6', 'subNum'=>'1'],
        ],        
        '5' => [
            'name' => 'Location',
            'url' => "",
            'route_target' => 'location.venue',
            'route_param' => [],
        ]
    ],

    'sub_menu' => [
        '1' => [
            '1' => [
                'name' => 'Overview',
                'url' => "",
                'route_target' => 'about.overview',
                'route_param' => [],
            ],
            '2' => [
                'name' => 'Welcome Message',
                'url' => "",
                'route_target' => 'about.welcome',
                'route_param' => [],
            ],
            '3' => [
                'name' => 'About Society',
                'url' => "",
                'route_target' => 'about.society',
                'route_param' => [],
            ],
            '4' => [
                'name' => 'Committee',
                'url' => "",
                'route_target' => 'about.committee',
                'route_param' => [],
            ],
            '5' => [
                'name' => 'Notice',
                'url' => "",
                'route_target' => 'board.list',
                'route_param' => ['code'=>'notice'],
            ],
            '6' => [
                'name' => 'Contact Info',
                'url' => "",
                'route_target' => 'about.contact',
                'route_param' => [],
            ],
        ],
        '2' => [
            '1' => [
                'name' => 'Program at a glance',
                'url' => "",
                'route_target' => 'ready',
                'route_param' => ['mainNum'=>'2', 'subNum'=>'1'],
            ],
            '2' => [
                'name' => 'Program in Detail',
                'url' => "",
                'route_target' => 'ready',
                'route_param' => ['mainNum'=>'2', 'subNum'=>'2'],
            ],
            '3' => [
                'name' => 'Plenary Lectures',
                'url' => "",
                'route_target' => 'program.speakers',
                'route_param' => ['mainNum'=>'2', 'subNum'=>'3'],
            ],
            '4' => [
                'name' => 'Special Symposia',
                'url' => "",
                'route_target' => 'ready',
                'route_param' => ['mainNum'=>'2', 'subNum'=>'4'],
            ]
        ],
        '3' => [
            '1' => [
                'name' => 'Abstract Guidelines',
                'url' => "",
                'route_target' => 'ready',
                'route_param' => ['mainNum'=>'3', 'subNum'=>'1'],
            ],
            '2' => [
                'name' => 'Online Submission',
                'url' => "",
                'route_target' => 'ready',
                'route_param' => ['mainNum'=>'3', 'subNum'=>'2'],
            ],
            '3' => [
                'name' => 'Abstract Review & Modification',
                'url' => "",
                'route_target' => 'ready',
                'route_param' => ['mainNum'=>'3', 'subNum'=>'3'],
            ],
            '4' => [
                'name' => 'Presentation Guidelines',
                'url' => "",
                'route_target' => 'ready',
                'route_param' => ['mainNum'=>'3', 'subNum'=>'4'],
            ]
        ],
        '4' => [
            '1' => [
                'name' => 'Registration Guidelines',
                'url' => "",
                'route_target' => 'registration.guide',
                'route_param' => [],
            ],
            '2' => [
                'name' => 'Go to Register',
                'url' => "",
                'route_target' => 'apply.registration',
                'route_param' => ['step'=>'1'],
            ],
            '3' => [
                'name' => 'Registration Confirmation and Receipt',
                'url' => "",
                'route_target' => 'registration.search',
                'route_param' => [],
            ],
        ],
        '5' => [
            '1' => [
                'name' => 'Venue',
                'url' => "",
                'route_target' => 'location.venue',
                'route_param' => [],
            ],
            '2' => [
                'name' => 'Map & Transportation',
                'url' => "",
                'route_target' => 'ready',
                'route_param' => ['mainNum'=>'5', 'subNum'=>'2'],
            ],
            '3' => [
                'name' => 'Accommodation',
                'url' => "",
                'route_target' => 'ready',
                'route_param' => ['mainNum'=>'5', 'subNum'=>'3'],
            ],
        ],
        '6' => [            
            '1' => [
                'name' => 'Sponsors Guidelines',
                'url' => "",
                'route_target' => 'ready',
                'route_param' => ['mainNum'=>'6', 'subNum'=>'1'],
            ]
        ]
    ],
    
    'admin_menu' => [
        '1' => [
            'name' => 'Registration',
            'url' => "",
            'route_target' => 'admin.registration.list',
            'route_param' => [],
        ],
        '2' => [
            'name' => 'Abstract',
            'url' => "",
            'route_target' => 'admin.registration.list',
            'route_param' => [],
        ],
    ],
]

?>