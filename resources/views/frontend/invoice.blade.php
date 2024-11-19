<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Ride Invoice</title>
	<link rel="stylesheet" href="">
</head>

<body style="margin: 0;font-family: system-ui;">
	<section style="max-width: 400px;margin: 0 auto;padding:40px 10px 0;position: relative;height: 90vh;">  		
		<div class="item" style="padding: 10px;">
			<div class="image" style="text-align: center;">
				<img src="<?= isset($client->logo['original']) ? $client->logo['original'] : 'default-logo.png'; ?>" alt="Logo" style="max-width: 100px;margin: 0 auto 15px;">
			</div>
			<div class="heading" style="text-align: center;margin-bottom: 20px;">
				<h2 style="margin-top: 10px;margin-bottom:5px;text-align: center;font-size: 25px;">Ride Invoice</h2>
				<p style="margin: 0;padding-bottom: 10px;">Thank you for riding with us!</p>
			</div>
			<div class="" style="clear: both;">
				<h6 style="margin: 0 0 5px;font-size: 14px;font-weight: 400;float: left;">
					<span style="font-weight: 500;">To:</span> <?= isset($response['order_details']['user']['name']) ? $response['order_details']['user']['name'] : 'N/A'; ?>
				</h6>
				<h6 style="margin: 0 0 5px;font-size: 14px;font-weight: 400;float: right;">
					<span style="font-weight: 500;">Date:</span> <?= isset($response['tasks'][0]['updated_at']) ? $response['tasks'][0]['updated_at'] : 'N/A'; ?>
				</h6>
				<div style="clear: both;">
					<p style="margin-bottom: 0;">
						<span style="font-weight: 500;">Tax invoice issued by:</span> <?= isset($response['order_details']['vendor']['name']) ? $response['order_details']['vendor']['name'] : 'N/A'; ?>
					</p>
				</div>
			</div>
			<div class="">				
				<ul style="padding: 0;border-bottom: 1px solid #ddd; margin:20px 0 10px; padding-bottom: 10px;">
					<li style="list-style: none;font-size: 14px;font-weight: 500;padding-bottom: 5px;clear: both;">
						Order ID: <span style="display: inline-block;font-size: 14px;font-weight: 500;color: #000;float: right;"><?= isset($response['order']['order_number']) ? $response['order']['order_number'] : 'N/A'; ?></span>
					</li>
					<li style="list-style: none;font-size: 14px;font-weight: 500;padding-bottom: 5px;clear: both;">
						Driver's Name: <span style="display: inline-block;font-size: 14px;font-weight: 500;color: #000;float: right;"><?= isset($response['agent']['name']) ? $response['agent']['name'] : 'N/A'; ?></span>
					</li>
					<li style="list-style: none;font-size: 14px;font-weight: 500;padding-bottom: 5px;clear: both;">
						Pickup Location: <span style="display: inline-block;font-size: 14px;font-weight: 500;color: #000;float: right;"><?= isset($response['tasks'][0]['address']) ? $response['tasks'][0]['address'] : 'N/A'; ?></span>
					</li>
					<li style="list-style: none;font-size: 14px;font-weight: 500;padding-bottom: 5px;clear: both;">
						Drop-off Location: <span style="display: inline-block;font-size: 14px;font-weight: 500;color: #000;float: right;"><?= isset($response['tasks'][1]['address']) ? $response['tasks'][1]['address'] : 'N/A'; ?></span>
					</li>				
				</ul>
				<ul style="padding: 0;">
					<li style="list-style: none;font-size: 14px;font-weight: 500;padding-bottom: 5px;">Description 
						<span style="display: inline-block;font-size: 16px;font-weight: 500;color: #000;float: right;">Amount</span>
					</li>
					<li style="list-style: none;font-size: 14px;font-weight: 500;padding-bottom: 5px;">Subtotal: 
						<span style="display: inline-block;font-size: 16px;font-weight: 600;color: #000;float: right;"><?= isset($response['order_details']['subtotal_amount']) ? $response['order_details']['subtotal_amount'] : 'N/A'; ?></span>
					</li>
					<li style="list-style: none;font-size: 16px;font-weight: 500;padding-bottom: 5px;">Taxes: 
						<span style="display: inline-block;font-size: 16px;font-weight: 600;color: #000;float: right;"><?= isset($response['order_details']['taxable_amount']) ? $response['order_details']['taxable_amount'] : 'N/A'; ?></span>
					</li>
					<li style="list-style: none;font-size: 16px;font-weight: 500;padding-bottom: 5px;">Total Fare: 
						<span style="display: inline-block;font-size: 16px;font-weight: 600;color: #000;float: right;"><?= isset($response['order_details']['order_detail']['payable_amount']) ? $response['order_details']['order_detail']['payable_amount'] : 'N/A'; ?></span>
					</li>
				</ul>
			</div>
		</div>
		<div class="footer" style="text-align: center;margin-top: 50px;border-top: 1px solid #ddd;padding-top: 15px;">
			<p style="font-size: 14px;margin: 0 0 5px;">For any inquiries, please contact us at 
				<a href="mailto:<?= isset($client->email) ? $client->email : ''; ?>" style="font-weight: 600;color: blue;cursor: pointer;text-decoration: none;"><?= isset($client->email) ? $client->email : 'N/A'; ?></a>
			</p>
			<p style="font-size: 14px;margin: 0 0 5px;">Thank you for choosing our service!</p>
		</div>
	</section>
</body>
</html>
