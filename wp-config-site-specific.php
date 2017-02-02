<?php
/**
 * Additional wp-config rules not tracked by Git.
 * Generally speaking, this would be the place to house any of our own 301 redirects needed for the site.

 Notes: Make sure that $newurl doesn't have trailing slash. The 'REQUEST URI' server variable includes this.

 Example from Pantheon: 
 =============================================
// Redirect subdomain to a specific path.
if (isset($_SERVER['PANTHEON_ENVIRONMENT']) &&
  ($_SERVER['HTTP_HOST'] == 'fullcircle.engineering.asu.edu') &&
  (php_sapi_name() != "cli")) {
  $newurl = 'https://fullcircle.asu.edu'. $_SERVER['REQUEST_URI'];
  header('HTTP/1.0 301 Moved Permanently');
  header("Location: $newurl");
  exit();
}

*/