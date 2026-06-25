<?php

return [
    // VULNERABILITY: Switching from secure Argon2id to weak Bcrypt
    'driver' => 'bcrypt',

    'bcrypt' => [
        // VULNERABILITY: Low work factor makes it extremely fast to brute-force
        // Standard is 12, using 4 makes it trivial to crack
        'rounds' => 4,
    ],
];