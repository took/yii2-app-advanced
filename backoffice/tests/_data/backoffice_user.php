<?php

return [
    [
        'id_backoffice_user' => 1,
        'username' => 'superadmin',
        'auth_key' => 'HP187Mvq7Mmm3CTU80dLkGmni_FUH_lR',
        //password_0
        'password_hash' => '$2y$13$EjaPFBnZOQsHdGuHI.xvhuDp1fHpo8hKRSk6yshqa9c5EG8s3C3lO',
        'password_reset_token' => null,
        'verification_token' => null,
        'email' => 'superadmin@example.com',
        'roles' => 'edit-backoffice-user,edit-frontpage-user,edit-key-value-pairs,view-key-value-pairs',
        'status' => 10,
        'created_at' => '2024-01-01 00:00:00',
        'updated_at' => '2024-01-01 00:00:00',
    ],
    [
        'id_backoffice_user' => 2,
        'username' => 'kvadmin',
        'auth_key' => 'EdKfXrx88weFMV0vIxuTMWKgfK2tS3Lp',
        // Test1234
        'password_hash' => '$2y$13$g5nv41Px7VBqhS3hVsVN2.MKfgT3jFdkXEsMC4rQJLfaMa7VaJqL2',
        'password_reset_token' => 'ExzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1I_1402312317',
        'verification_token' => null,
        'email' => 'kvadmin@example.com',
        'roles' => 'edit-key-value-pairs,view-key-value-pairs',
        'status' => 10,
        'created_at' => '2024-01-02 00:00:00',
        'updated_at' => '2024-01-02 00:00:00',
    ],
    [
        'id_backoffice_user' => 3,
        'username' => 'fpadmin',
        'auth_key' => 'O87GkY3_UfmMHYkyezZ7QLfmkKNsllzT',
        //Test1234
        'password_hash' => '$2y$13$d17z0w/wKC4LFwtzBcmx6up4jErQuandJqhzKGKczfWuiEhLBtQBK',
        'password_reset_token' => null,
        'verification_token' => null,
        'email' => 'fpadmin@example.com',
        'roles' => 'edit-frontpage-user,invite-frontpage-user',
        'status' => 10,
        'created_at' => '2024-01-04 00:00:00',
        'updated_at' => '2024-01-04 00:00:00',
    ],
    // User with a valid (fresh) password reset token — used by ResetPasswordFormTest
    [
        'id_backoffice_user' => 5,
        'username' => 'boadmin',
        'auth_key' => 'iwTNae9t34OmnK6l4vT4IeaTk-YWI2Rv',
        // Test1234
        'password_hash' => '$2y$13$CXT0Rkle1EMJ/c1l5bylL.EylfmQ39O5JlHJVFpNn618OUS1HwaIi',
        'password_reset_token' => 't5GU9NwpuGYSfb7FEZMAxqtuz2PkEvv_' . time(),
        'verification_token' => null,
        'email' => 'boadmin@example.com',
        'roles' => 'edit-backoffice-user',
        'status' => 10,
        'created_at' => '2024-01-05 00:00:00',
        'updated_at' => '2024-01-05 00:00:00',
    ],
    // Legacy user for backward compatibility
    [
        'id_backoffice_user' => 4,
        'username' => 'erau',
        'auth_key' => 'tUu1qHcde0diwUol3xeI-18MuHkkprQI',
        // password_0
        'password_hash' => '$2y$13$nJ1WDlBaGcbCdbNC5.5l4.sgy.OMEKCqtDQOdQ2OWpgiKRWYyzzne',
        'password_reset_token' => 'RkD_Jw0_8HEedzLk7MM-ZKEFfYR7VbMr_1392559490',
        'created_at' => '2014-02-16 02:04:50',
        'updated_at' => '2014-02-16 02:04:50',
        'email' => 'sfriesen@jenkins.info',
        'roles' => 'edit-backoffice-user,edit-frontpage-user,edit-key-value-pairs,view-key-value-pairs',
        'status' => 10,
    ],
];
