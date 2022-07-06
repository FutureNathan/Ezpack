<?php

#################################################################################################### --- PATH STRUCTURE

# Physical directory structure of the website. These directories physically exist on the server.

const PATH_PRIVATE              = __DIR__ . '/../';

const PATH_PRIVATE_FUNCTIONS    = PATH_PRIVATE . 'functions/';
const PATH_PRIVATE_I18N         = PATH_PRIVATE . 'i18n/';
const PATH_PRIVATE_HANDLERS     = PATH_PRIVATE . 'handlers/';
const PATH_PRIVATE_TEMPLATES    = PATH_PRIVATE . 'templates/';
const PATH_PRIVATE_THIRD_PARTY  = PATH_PRIVATE . 'third-party/';
const PATH_PRIVATE_VIEWS        = PATH_PRIVATE . 'views/';

const PATH_PRIVATE_ERROR_LOG    = PATH_PRIVATE . 'error_log';

# Logical directory structure of the website. These directories DO NOT physically exist on the server.
# We use these logical directories, in combination with htaccess rewrites, for the purpose of readable
# or "pretty" URLs, and also to hide the real structure of the website.

const PATH_LOGICAL_PUB        = WEBSITE_BASE_URL . 'pub/';
const PATH_LOGICAL_PUB_FILE   = PATH_LOGICAL_PUB . 'file/';

?>
