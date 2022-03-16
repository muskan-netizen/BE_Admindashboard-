<html>

<head>
<title>Hellow World</title>
</head>
<body>


<?php
$zillow_id = 'X1-ZWz1ip5o7rayob_a57uf';

$search = '155 Demar Blvd';
$citystate = 'Canonsburg PA';
$address = urlencode($search);
$citystatezip = urlencode($citystate);

$url = "http://www.zillow.com/webservice/GetDeepSearchResults.htm?zws-id=$zillow_id&address=$address&citystatezip=$citystatezip";

$result = file_get_contents($url);
$data = simplexml_load_string($result);

echo $data->response->results->result->lotSizeSqFt . "<br>";

?>

</body>
</html>