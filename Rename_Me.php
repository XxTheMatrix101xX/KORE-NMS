<?php
//call easykore (Forked from EasyBitcoin-PHP)
require_once('easykore.php');

/*
Many LAMP settups will run into a curl issue
To fix this make sure you have the latest curl
version and then restart the apache2 server

sudo apt-get install php-curl
sudo service apache2 restart
*/

//connect to KORE's RPC with username and password
$kore = new Kore('user','pass','localhost','9932');

//core RPC calls
    //kore-cli getnetworkinfo :: Pulls all network information for parsing
    $network = $kore->getnetworkinfo();

    //kore-cli getinfo :: Pulls current wallet information
    $info = $kore->getinfo();

    //kore-cli getconnection count
    $connectioncount = $kore->getconnectioncount();

    //Pulls PoW && PoS difficulties
    $netdif = $kore->getdifficulty();

    //Pull ALL peer info
    $peerinfo = $kore->getpeerinfo(); //THIS NEEDS WORK WITH SPREADSHEET
    //pull errors
    $error = $kore->error;


//parsings
    //get the node's onion address
    $node = $network['localaddresses'][0]["address"];

    //get the current latest block from your chain
    $currentblock = $info["blocks"];

    //parse  the stake difficulty.
    $stakedif = $netdif["proof-of-stake"];

    //NEED TO PULL "addr" and "synced_blocks" FROM $peerinfo THEN only display to spreadsheet.
    $peers = [];
    foreach ($peerinfo as $peer) {
     	$peers[] = [
     		'address'	=>	$peer['addr'],
     		'count'		=>	$peer['synced_blocks'],
		'color'		=>	($peer['synced_blocks'] < $currentblock)?'red':(($peer['synced_blocks'] > $currentblock)?'green':'white'),
     	];
     }

/*
highlight_string("<?php\n\n" . var_export($currentblock, true) . ";\n\n?>\n\n");
highlight_string("<?php\n\n" . var_export($peers, true) . ";\n\n?>\n\n");
*/

/* dump tests
//var_dump($error);
//echo "<br><br>";
//print_r($info);
//echo "<br><br>";
//var_dump($network);
//var_dump($info);
echo "<br><br>";
//echo $node[0]['version'];
//highlight_string("<?php\n\n" . var_export($info, true) . ";\n\n?>\n\n");
//echo "\n\n";
*/

/*
highlight_string("<?php\n\n" . var_export($peerinfo, true) . ";\n\n?>\n\n");
highlight_string("<?php\n\n" . var_export($peers, true) . ";\n\n?>\n\n");
*/

?>

<!DOCTYPE html>
<html lang="en">
	<head>
	    <title><?= $node ?></title>
	    <meta charset="utf-8">
		<style>
			body {
				background-color:#3686be;
				border: 10px solid #3686be;
				background-image: url("https://media.discordapp.net/attachments/387383860650049547/403656072713469962/Kback.png?width=750&height=750");
				background-repeat: no-repeat;
				background-attachment: fixed;
				background-position: 100px;
				color:#FFF;
			}

			.tooltip {
			    position: relative;
			    display: inline-block;
			    border-bottom: 1px dotted black;
			}

			.tooltip .tooltiptext {
			    visibility: hidden;
			    width: 150px;
			    background-color: black;
			    color: #fff;
			    text-align: center;
			    border-radius: 6px;
			    padding: 5px 0;

			    /* Position the tooltip */
			    position: absolute;
			    z-index: 1;
			}

			.tooltip:hover .tooltiptext {
			    visibility: visible;
			}

		</style>
	</head>
	<body>
	    <img src="http://165.227.48.124/wp-content/uploads/2017/11/logoweb.png" alt="" data-ww="360px" data-hh="200px" data-no-retina="" style="width: 307.6px; height: 170.889px; transition: none; text-align: inherit; line-height: 0px; border-width: 0px; margin: 0px; padding: 0px; letter-spacing: 0px; font-weight: 400; font-size: 10px;">
	    <br><br>
	    <div style="float: left;">
			Your node's current onion address: <b><?= $node ?></b>
			<br><br>
			Your node's current block: <b><?= $currentblock ?></b>
			<br><br>
			Your node's current connection count: <b><?= $connectioncount ?></b>
			<br><br>
			Network Staking Difficulty: <b><?= $stakedif ?></b>
		</div>
		<div style="float: right; margin-right: 400px; background-color: white;color: black;">
			<table border="1" style="color: black;">
				<thead>
					<tr>
						<th>Node Onion Address</th>
						<th><div class="tooltip">Block Count (?)
							<span class="tooltiptext">Please note that it is normal for nodes to fall behind up to five blocks from time to time this normal with all coins</span>
</div></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($peers as $peer) { ?>
						<tr style="background-color: <?= $peer['color'] ?>">
							<td><?= $peer['address'] ?></td>
							<td style="text-align: right;"><?= $peer['count'] ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<div style="clear:both;"></div>
		<br><br>
		<footer>
			<p>KORE NMS by TheMatrix101<p>
		</footer>
	</body>
</html>
<!---
/*

Special thanks:
To Hamza, for review of code, assisting with optimization and troubleshooting.
To You, for being curious and doing research.
To w3schools, for quick code grabs.

"Curiosity is the wick in the candle of learning." ~ William Arthur Ward

*/
---!>
