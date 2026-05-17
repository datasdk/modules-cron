<?php

return [
    'name' => 'Cron',
    'limitPrRequest' => 100,
    "whitelist" => ["email","notifications"],
    "timeout" => 25,
    "maxtime" => 25,


    'admin' => [

  
        'navigationbar' => [

            "group" => "Cron",

            "sorting" => 900,
   
            "link" => ["icon" => "fas fa-history", "name" => "Cronjobs",  "link" => "cron.tasks.index", "new_window" => false ],

            /*
            "submenu" => [

                ["icon" => "fas fa-history", "name" => "Cronjobs",  "link" => "module.cron.index", "new_window" => false ],
                ["icon" => "fas fa-history", "name" => "Schedule manage",  "link" => "module.cron.schedule.index", "new_window" => false ],
                
            ],
            */
        
        ], 
    


    ]
    
];
