<?php namespace ProcessWire;

$page_access = true;
if ($access == 'seller') {
    $page_access = false;
}
if ($access == 'receiver') {
    $page_access = false;
}