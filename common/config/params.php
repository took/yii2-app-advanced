<?php

return [
    'frontpageUrl' => 'https://example.com/',
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600 * 24,
    'user.passwordMinLength' => 12,
    'backofficeRoles' => [
        'view-key-value-pairs' => 'View KeyValue Pairs',
        'edit-key-value-pairs' => 'Edit KeyValue Pairs',
        'invite-frontpage-user' => 'Invite new frontpage user, resend invite links for frontpage user',
        'edit-frontpage-user' => 'Edit frontpage users (includes invite-frontpage-user)',
        'edit-backoffice-user' => 'Edit backoffice users'
    ]
];
