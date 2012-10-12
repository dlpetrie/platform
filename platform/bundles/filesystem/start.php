<?php

// Autoload classes
Autoloader::namespaces(array(
    'Filesystem' => Bundle::path('filesystem'),
));

// Set the global alias for Sentry
Autoloader::alias('Filesystem\\Filesystem', 'Filesystem');