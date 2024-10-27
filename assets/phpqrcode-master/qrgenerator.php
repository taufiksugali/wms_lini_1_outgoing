<?php
include('qrlib.php');
$data = $_GET['data'];

// Atur header untuk output gambar PNG
header('Content-Type: image/png');

// Tampilkan QR Code dengan data dari parameter
QRcode::png($data);
