<?php

include ('ValidateHtml.php');

$testSite1 = new ValidateHtml('http://www.example.com', false);
$testSite2 = new ValidateHtml('http://www.google.com', true);

echo '<pre>';
print_r($testSite1->htmlWarnings);
echo '</pre>';

echo '<pre>';
print_r($testSite2->htmlErrorsAndWarnings);
echo '</pre>';