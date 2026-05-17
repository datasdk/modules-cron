<?php

return [
    'success' => [
        'create' => 'Cron opgaven blev oprettet.',
        'update' => 'Cron opgaven blev opdateret.',
        'delete' => 'Cron opgaven blev slettet.',
        'activate' => 'Cron opgaven blev aktiveret.',
        'deactivate' => 'Cron opgaven blev deaktiveret.',
        'execute' => 'Cron opgaven blev udført.',
    ],
    
    'errors' => [
        'create' => 'Fejl ved oprettelse af cron opgave.',
        'update' => 'Fejl ved opdatering af cron opgave.',
        'delete' => 'Fejl ved sletning af cron opgave.',
        'not_found' => 'Cron opgaven blev ikke fundet.',
        'execute' => 'Fejl ved udførelse af cron opgave.',
    ],
    
    'validation' => [
        'description_required' => 'Beskrivelse er påkrævet.',
        'command_required' => 'Kommando er påkrævet.',
        'expression_required' => 'Cron-udtryk er påkrævet.',
        'expression_invalid' => 'Cron-udtrykket er ugyldigt.',
        'timezone_invalid' => 'Tidszone er ugyldig.',
    ],
    
    'labels' => [
        'description' => 'Beskrivelse',
        'command' => 'Kommando',
        'parameters' => 'Parametre',
        'expression' => 'Cron Udtryk',
        'timezone' => 'Tidszone',
        'is_active' => 'Aktiv',
        'dont_overlap' => 'Overlap ikke',
        'run_in_maintenance' => 'Kør i vedligeholdelsestilstand',
        'run_on_one_server' => 'Kør på én server',
        'run_in_background' => 'Kør i baggrunden',
        'notification_email_address' => 'Email Notifikation',
        'notification_phone_number' => 'SMS Notifikation',
        'notification_slack_webhook' => 'Slack Webhook',
        'auto_cleanup_type' => 'Auto Ryd Op Type',
        'auto_cleanup_num' => 'Antal at Bevare',
        'created_at' => 'Oprettet',
        'updated_at' => 'Opdateret',
        'last_ran_at' => 'Sidst Kørt',
        'average_runtime' => 'Gennemsnitlig Køretid',
        'upcoming' => 'Næste Kørsel',
        'status' => 'Status',
        'active' => 'Aktiv',
        'inactive' => 'Inaktiv',
        'success' => 'Succes',
        'failed' => 'Fejlede',
    ],
    
    'actions' => [
        'create' => 'Opret Cron Opgave',
        'edit' => 'Rediger Cron Opgave',
        'delete' => 'Slet Cron Opgave',
        'view' => 'Se Cron Opgave',
        'execute' => 'Udfør Cron Opgave',
        'activate' => 'Aktiver Cron Opgave',
        'deactivate' => 'Deaktiver Cron Opgave',
        'back_to_list' => 'Tilbage til liste',
    ],
    
    'confirmation' => [
        'delete' => 'Er du sikker på, at du vil slette denne cron opgave?',
        'execute' => 'Er du sikker på, at du vil udføre denne cron opgave nu?',
        'activate' => 'Er du sikker på, at du vil aktivere denne cron opgave?',
        'deactivate' => 'Er du sikker på, at du vil deaktivere denne cron opgave?',
    ],
    
    'status' => [
        'active' => 'Aktiv',
        'inactive' => 'Inaktiv',
        'running' => 'Kører',
        'completed' => 'Fuldført',
        'failed' => 'Fejlede',
        'pending' => 'Afventer',
    ],
    
    'frequencies' => [
        'every_minute' => 'Hvert minut',
        'every_five_minutes' => 'Hvert 5. minut',
        'every_ten_minutes' => 'Hvert 10. minut',
        'every_fifteen_minutes' => 'Hvert 15. minut',
        'every_thirty_minutes' => 'Hvert 30. minut',
        'hourly' => 'Hver time',
        'daily' => 'Dagligt',
        'weekly' => 'Ugentligt',
        'monthly' => 'Månedligt',
        'yearly' => 'Årligt',
    ],
    
    'cleanup' => [
        'none' => 'Ingen',
        'results' => 'Bevar sidste',
        'days' => 'Bevar dage',
        'description' => 'Antal resultater/dage at beholde',
    ],
    
    'table' => [
        'no_tasks' => 'Ingen cron opgaver fundet.',
        'no_results' => 'Ingen udførelsesresultater.',
    ],
];