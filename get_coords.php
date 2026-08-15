<?php
function queryOSM($q) {
    $url = "https://nominatim.openstreetmap.org/search?q=" . urlencode($q) . "&format=json&limit=5";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "SFC-Attendance-App-Geocoding-Fetch-Agent/1.0");
    $res = curl_exec($ch);
    curl_close($ch);
    return json_decode($res, true);
}

echo "Query 1: Jalan Pala Raya, Pamulang\n";
print_r(queryOSM("Jalan Pala Raya, Pamulang"));

echo "\nQuery 2: Pondok Cabe Golf\n";
print_r(queryOSM("Pondok Cabe Golf"));
?>
