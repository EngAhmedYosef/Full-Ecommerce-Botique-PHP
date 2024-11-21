<?php 

function sendEmail($to, $title, $body)
{
    $header = "From: support@waelabohamza.com " . "\n" . "CC: waeleagle1243@gmail.com";
    mail($to, $title, $body, $header);
}