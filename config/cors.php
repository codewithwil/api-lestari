<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],  // This allows all HTTP methods (GET, POST, PUT, DELETE, etc.)
    'allowed_origins' => ['http://localhost:3000'], // This should match your frontend's URL
    'allowed_headers' => ['*'],  // Allow any headers
    'supports_credentials' => true, // Allow credentials (cookies, authorization headers, etc.)
];
