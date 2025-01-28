<?php
$Waktu = 'Malam';
if (date('H') >= 3) $Waktu = 'Pagi';
if (date('H') >= 9) $Waktu = 'Siang';
if (date('H') >= 15) $Waktu = 'Sore';
if (date('H') >= 18) $Waktu = 'Malam';
