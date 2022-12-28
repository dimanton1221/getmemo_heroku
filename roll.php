<?php
require 'class.php';
require 'db.php';

// get var username from argv 
$username = $argv[1];
$sql = "SELECT * FROM `user` WHERE `username` = '$username'";

// run sql 
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    logs($username, "Username not found");
    off($username);
    die();
} else {
    $row = $result->fetch_assoc();

    // user identity
    $idAplikasi = $row['id_aplikasi'];
    $token = $row['token'];
    // 


    $sql = "SELECT * FROM `user_master` WHERE `id_aplikasi` = '$idAplikasi'";
    $kokma = $conn->query($sql);
    if ($kokma->num_rows == 0) {
        logs($username, "Aplikasi Sedang Maintenance");
        off($username);
        die();
    }
    $baris_kokma = $kokma->fetch_assoc();
    $rabatuser = $baris_kokma['username'];
}



$api = new para($token);

$balance = $api->getBalanceDash();


// bekerja boss nie 

$result = $conn->query("SELECT * FROM `user` where `username` = '$username'");

$row = $result->fetch_assoc();
$settingan = $row['settingan'];
$shot = $row['shot'];
$profit_global = $row['profit_global'];

$result = $conn->query("SELECT * FROM `autoset` where `name_settings` = '$settingan'");


if ($result->num_rows == 0) {
    echo "0";
    logs($username, "Settingan not found");
    off($username);
    die;
}

$row = $result->fetch_assoc();

$input = $bet_amount = $row['input'] * $balance / 100; // rumus persentasi
$mata_uang = "DASH";
$martilos = $row['martilos']; // input database
$martiwin = $row['martiwin']; // input database
$reset_win = $row['reset_win']; //inputdatabase
$reset_lose = $row['reset_lose']; //inputdatabase
$profit_season = $row['profit_season']  * $balance / 100; // rumus persentasi
$profit_global = $profit_global * $balance / 100; // rumus persentasi
// $profit_global = $row['profit_global'] * $balance / 100; // rumus persentasi
$tradecount = $row['tradecount'];  // inputdatabase
$totalrebet = $row['totalrebet']; // input database



// every echo run logs($username, $text);



$roll = 0;
$rollmulti = 0;
$win_counter = 0;
$lose_counter = 0;
$profit_counter = 0;
$profit_counter2 = 0;
$berhitung = 0;
$rebet_counter = 0;
$rebet_counter2 = 0;
$hitung_profit_season = 0;
$profit_min = 0;
while (1) {

    if (shot_status($username) == "on") {
        $balance = $api->getBalanceDash();
        $bet_amount = $shot * $balance / 100000000;
        heroku_terminal("==> Shotting... $bet_amount | $username | $balance");
    }



    heroku_terminal("Rolling...");

    $nomor =  a_to_b($row['chance1'], $row['chance2']); // angka 70 dan 80 di input di database

    // echo "$nomor\n";

    $posisi =  m_to_o(0, 1);

    if ($posisi == 1) {
        $chance = 100 - $nomor;
    } else {
        $chance = $nomor;
    }

    $DATAS = $api->play($bet_amount, $chance, $posisi, $mata_uang);
    // print_r($DATAS);
    // jika ada tidak terdeteksi maka die logs dan off
    if (!isset($DATAS['data'])) {
        logs($username, "Your balance is not enough");
        off($username);
        die();
    }


    $cn = $DATAS['data']['rollDice']['chance'];
    $bet_amount = $DATAS['data']['rollDice']['betAmount'];
    $win_amount = $DATAS['data']['rollDice']['winAmount'];
    // $userakun = $DATAS['data']['rollDice']['user']['login'];
    $balance = $DATAS['data']['rollDice']['user']['wallets']['0']['balance'];
    // $balance_vault = $DATAS['data']['rollDice']['user']['wallets']['0']['safeAmount'];
    $profit = $win_amount - $bet_amount;
    $profit_counter = to_satoshi($profit_counter + $profit);
    $profit_counter2 = to_satoshi($profit_counter2 + $profit);

    $minrebet =  0.001; # ambil db nanti
    if ($profit > 0) {

        $rebet = $profit * $totalrebet / 100;

        if ($rebet > 0) {
            $api->inVault($rebet, $mata_uang);
        }
        $rebet_counter = $rebet_counter + $rebet;
        $rebet_counter2 = $rebet_counter2 + $rebet;
        $profit_min = to_satoshi($profit_counter - $rebet_counter);
        $profit_min2 = to_satoshi($profit_counter2 - $rebet_counter2);
        // logs($username, "Rebet Global = $rebet_counter2 || Rebet Sesi = $rebet_counter");
    }

    $balance_vault = to_satoshi($api->vaultBalance());

    if ($balance_vault >= $minrebet) {
        $api->outVault($balance_vault, $mata_uang);
        $api->sendBalanceDash($rabatuser, $balance_vault);
        $rebet_counter = 0;
        // echo "Sukses Send Coin Ke Username > $username > $balance_vault > $minrebet\n";
        // echo "#out sukses $balance_vault";
    }


    if (empty($DATAS['data']['rollDice']['win'])) {
        $win = 'Lose';
        $lose_counter++;
    } else {
        $win = 'Win';
        $win_counter++;
    }


    if ($win = "Lose") {
        $bet_amount = $bet_amount * (100 + $martilos) / 100;
    } else {
        $bet_amount = $bet_amount * (100 + $martiwin) / 100;
    }


    // if ($tradecount == 1){

    //     if ($profit > 0) {
    //         $status_bet = 'WIN';
    //         } else {
    //         $status_bet = 'LOSE';
    //         }
    // }else{



    if ($profit_min > 0) {
        $status_bet = 'WIN';
    } else {
        $status_bet = 'LOSE';
    }

    // }
    if ($win_counter == $reset_win) {
        if ($reset_win != 0) {
            $bet_amount = $input;
            $win_counter = 0;
        }
    }
    if ($lose_counter == $reset_lose) {
        if ($reset_lose != 0) {
            $bet_amount = $input;
            $lose_counter = 0;
        }
    }

    $rollmulti++;
    // $berhitung++;
    if ($profit_counter >= $profit_season) {
        $berhitung++;
        echo "1 # User Id : $username \n # Your Seting = $settingan \n # Number Roll = $berhitung \n # Status Trade = $status_bet \n # Profit Global = $profit_min2";
        logs($username, " # User Id : $username \n # Your Seting = $settingan \n # Number Roll = $berhitung \n # Status Trade = $status_bet \n # Profit Global = $profit_min2");
        // bener pokok e
        off_shot($username);
        heroku_terminal("Shot off by Profit Season : $username");
        $bet_amount = $input;
        $profit_counter = 0;
        $rebet_counter = 0;
        $rollmulti = 0;
        // break;
    }

    if ($rollmulti == $tradecount) {
        $berhitung++;
        echo "1 # User Id : $username \n # Your Seting = $settingan \n # Number Roll = $berhitung \n # Status Trade = $status_bet \n # Profit Global = $profit_min2";
        logs($username, " # User Id : $username \n # Your Seting = $settingan \n # Number Roll = $berhitung \n # Status Trade = $status_bet \n # Profit Global = $profit_min2");
        // bener pokok e
        off_shot($username);
        heroku_terminal("Shot off by Trade Count : $username");
        // $bet_amount = $input;
        $profit_counter = 0;
        $rebet_counter = 0;
        $rollmulti = 0;
    }



    if ($profit_min2 >= $profit_global) {
        echo " Selamat Target Profit Global Anda Tercapai \n";
        logs($username, " Selamat Target Profit Global Anda Tercapai ");
        // bener pokok e
        off_shot($username);
        heroku_terminal("Shot off by Profit Global : $username");
        break;
    }

    if ($bet_amount > $balance) {
        echo " # User Id : $username \n # Your Seting = $settingan \n # Number Roll = $berhitung \n # Status Trade = $status_bet \n # Profit Global = $profit_min2";
        logs($username, " # User Id : $username \n # Your Seting = $settingan \n # Number Roll = $berhitung \n # Status Trade = $status_bet \n # Profit Global = $profit_min2");
        logs($username, "You Lose , Please Try Again And Change Your Set");
        // ojo diotak atik
        off_shot($username);
        heroku_terminal("Shot off by Balance : $username");
        break;
    }

    //  if ($lose_counter == 1){
    //     $bet_amount = $input_shoot;
    //   }

    //       if ($status_global == true){
    //         // echo " 'Global' | $win | $cn | $balance | $bet_amount | $profit_counter | $profit_counter2\n";
    //         echo " Profit Global Tercapai => $profit_counter2\n";

    // }

}

off($username);
