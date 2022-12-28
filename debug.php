<?php

// require "db.php";
require "class.php";
$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJwYXJhZGljZS5pbiIsImF1ZCI6InBhcmFkaWNlLmluIiwiaWF0IjoxNjcxNzczODc0LCJuYmYiOjE2NzE3NzM4NzQsImRhdGEiOnsiaWQiOiIxMzk2MjYiLCJsb2dpbiI6Imtvcmtsb2siLCJrZXkiOiJsMnh2ZDU3dmFRUGNuTXhmUFJ1aE5UQk1tRUFpV1VINSJ9fQ.QrMbx_ZWhaM9iBmv34A_0xzRRimBg_wiuTk5QehqnuaRtCWYbMlhyh0b_tNWFBFB8Q5ysTEJTNhzTuVOZXq38G7yvbkqIHjdVYgAINaVGMiRRKHMlQ9ctLeeQInkGsIgCkuXW1ybmbbVusIWpqKGVSXh5e8bUhCBTftrRDjtrIrX2UO0iMMd1Q_oViHi-9wSe_oREjF-g7bpi5VNAS-ZcnkHZ6Uzis0oivMfL07wGIYprDmO_LH7MjzIVhEyd4TCSBPemyDyPUZ3xkb_WW8cVem_XLpqhcOcqQ-wALpHrBlRPKzfIAKu22oNoj_otQWqXO5Ypc71v--nYaFK0vin2g";
$api = new para($token);
// $DATAS = $api->getMe();
$DATAS = $api->withdrawDash('wew', "0");
print_r($DATAS);


die;
$cn = $DATAS['data']['rollDice']['chance'];
$bet_amount = $DATAS['data']['rollDice']['betAmount'];
$win_amount = $DATAS['data']['rollDice']['winAmount'];
$balance = $DATAS['data']['rollDice']['user']['wallets']['0']['balance'];


echo $balance;
// print_r($DATAS);

die;
$result = $conn->query("SELECT * FROM `user` where `username` = 'izzam'");

$row = $result->fetch_assoc();
$settingan = $row['settingan'];



$result = $conn->query("SELECT * FROM `autoset` where `name_settings` = '$settingan'");


if ($result->num_rows == 0) {
  echo "0";
  die;
}



$row = $result->fetch_assoc();
$apa = $row;

print_r($apa);



die;
off("izzam");
logs("izzam", "apasih");


die;
$jayParsedAry = [
  'operationName' => 'rollDice',
  'variables' => [
    'betAmount' => '0.00000001',
    'number' => '47.60',
    'side' => 'ABOVE',
    'currency' => 'PRDC'
  ],
  'query' => 'mutation rollDice($number: Float!, $betAmount: Float!, $side: RollSideEnum!, $currency: CurrencyEnum!) {
  rollDice(
    number: $number
    betAmount: $betAmount
    side: $side
    currency: $currency
  ) {
    id
    number
    roll
    rollSide
    win
    betAmount
    winAmount
    currency
    multiplier
    chance
    game
    bets {
      pocket
      payout
      win
      bet
      __typename
    }
    winLines {
      id
      __typename
    }
    slotGame {
      name
      __typename
    }
    offsets
    user {
      wallets {
        ...FRAGMENT_USER_WALLET
        __typename
      }
      lastActivity
      loyaltyLevel {
        level {
          id
          category
          level
          __typename
        }
        __typename
      }
      id
      login
      privacySettings {
        isPMNotificationsEnabled
        isWageredHidden
        isAnonymous
        __typename
      }
      __typename
    }
    __typename
  }
}

fragment FRAGMENT_USER_WALLET on Wallet {
  address
  balance
  bonus
  rakeback
  safeAmount
  currency
  __typename
}
'
];

echo json_encode($jayParsedAry);
