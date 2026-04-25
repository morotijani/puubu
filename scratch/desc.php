<?php
$c = new PDO('mysql:host=localhost;dbname=puubu', 'root', '');
print_r($c->query('DESCRIBE election')->fetchAll(PDO::FETCH_ASSOC));
