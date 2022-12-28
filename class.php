<?php

class para
{
  // make var username
  public $username;
  public function __construct($token)
  {
    $this->token = $token;

    if ($this->checkToken() == false) {
      // throw new Exception('0');
      // echo 1;
      die("0");
    }
  }


  // http request function
  public function http_request($data)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.paradice.in/api.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'X-Access-Token: ' . $this->token;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
      echo 'Error:' . curl_error($ch);
    } else {

      return json_decode($result, true);
    }
    curl_close($ch);
  }
  // getMe function
  public function getMe()
  {

    $data = [
      "variables" => [],
      "query" => "{
   me {
     ...FRAGMENT_COMPLETE_USER_DATA
     violations {
       __typename
     }
     __typename
   }
 }
 
 fragment FRAGMENT_COMPLETE_USER_DATA on User {
   id
   login
   userRoles
   messages
   lastActivity
   avatar
   likes
   friendsIds
   token {
     token
     __typename
   }
   favouriteAchievements {
     id
     key
     __typename
   }
   wallets {
     ...FRAGMENT_USER_WALLET
     __typename
   }
   ...FRAGMENT_USER_LOYALTY_LVL
   ...FRAGMENT_USER_PRIVACY_SETTINGS
   ...FRAGMENT_PRIVATE_USER_DATA
   __typename
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
 
 fragment FRAGMENT_USER_PRIVACY_SETTINGS on User {
   privacySettings {
     isPMNotificationsEnabled
     isWageredHidden
     isAnonymous
     __typename
   }
   __typename
 }
 
 fragment FRAGMENT_USER_LOYALTY_LVL on User {
   loyaltyLevel {
     level {
       ...LOYALTY_LEVEL
       __typename
     }
     progress
     isTemporary
     endsIn
     __typename
   }
   __typename
 }
 
 fragment LOYALTY_LEVEL on LoyaltyLevel {
   category
   level
   id
   wagerRequiredUsd
   features {
     feature
     value
     __typename
   }
   __typename
 }
 
 fragment FRAGMENT_PRIVATE_USER_DATA on User {
   email
   confirmed
   unconfirmedEmail
   protected
   serverSeed
   serverSeedNonce
   serverSeedNext
   twoFactorResetDate
   twoFactorEnabled
   friendsIds
   clientSeed
   isLongTimeInactive
   token {
     token
     __typename
   }
   violations {
     ...FRAGMENT_MY_VIOLATIONS
     __typename
   }
   __typename
 }
 
 fragment FRAGMENT_MY_VIOLATIONS on UserViolations {
   isMultiAcc
   constantAffiliateCommissionRestriction
   __typename
 }
 "
    ];
    $request = $this->http_request($data);
    $this->username = $request['data']['me']['login'];
    return $request;
  }
  // get user balance DASH
  public function getBalanceDash()
  {
    // getMe
    $me = $this->getMe();
    // get balance
    $balance = $me['data']['me']['wallets'][3]['balance'];
    return $balance;
  }
  public function vaultBalance()
  {
    // getMe
    $me = $this->getMe();
    // get balance
    $balance = $me['data']['me']['wallets'][3]['safeAmount'];
    return $balance;
  }
  // get deposit address DASH
  public function getDepositAddressDash()
  {
    $data = [
      "operationName" => "depositAddress",
      "variables" => [
        "currency" => "DASH"
      ],
      "query" => 'mutation depositAddress($currency: CurrencyEnum!) {
          depositAddress(currency: $currency) {
            ...FRAGMENT_WALLET
            __typename
          }
        }
        
        fragment FRAGMENT_WALLET on Wallet {
          currency
          balance
          address
          bonus
          rakeback
          safeAmount
          __typename
        }
        '
    ];
    // get deposit address from array json decode
    $depositAddress = $this->http_request($data);
    $depositAddress = $depositAddress['data']['depositAddress']['address'];
    return $depositAddress;
  }
  // get balance DASH bank

  public function getBalanceDashBank()
  {
    $data = [
      "operationName" => "depositAddress",
      "variables" => [
        "currency" => "DASH"
      ],
      "query" => 'mutation depositAddress($currency: CurrencyEnum!) {
          depositAddress(currency: $currency) {
            ...FRAGMENT_WALLET
            __typename
          }
        }
        
        fragment FRAGMENT_WALLET on Wallet {
          currency
          balance
          address
          bonus
          rakeback
          safeAmount
          __typename
        }
        '
    ];
    // get balance from array json decode from safeAmount
    $balance = $this->http_request($data);
    $balance = $balance['data']['depositAddress']['safeAmount'];
    $balance = sprintf('%.8f', floatval($balance));
    return $balance;
  }

  // withdraw DASH

  public function withdrawDash($address, $amount)
  {

    $jayParsedAry = [
      "operationName" => "withdraw",
      "variables" => [
        "amount" => $amount,
        "address" => $address,
        "currency" => "DASH"
      ],
      "query" => 'mutation withdraw($currency: CurrencyEnum!, $amount: Float!, $address: String!, $twoFactor: String) {
     withdraw(
       currency: $currency
       amount: $amount
       address: $address
       twoFactor: $twoFactor
     ) {
       id
       amount
       __typename
     }
   }
   '
    ];


    // get withdraw from array json decode
    $withdraw = $this->http_request($jayParsedAry);

    return $withdraw;
  }

  // send balance
  public function sendBalanceDash($usename, $amount)
  {
    $data = [
      "variables" => [
        "amount" => $amount,
        "currency" => "DASH",
        "user" => $usename,
        "notifyChat" => false,
        "message" => "",
        "isPrivate" => true
      ],
      "query" => 'mutation ($currency: CurrencyEnum!, $amount: Float!, $user: ID!, $notifyChat: Boolean, $message: String, $isPrivate: Boolean) {
          giveTips(
            currency: $currency
            amount: $amount
            user: $user
            notifyChat: $notifyChat
            message: $message
            isPrivate: $isPrivate
          ) {
            id
            wallets {
              ...FRAGMENT_USER_WALLET
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
    // Send Balance from array json decode
    $withdraw = $this->http_request($data);
    if (isset($withdraw['errors']['0']['message'])) {
      return $withdraw['errors']['0']['message'];
    } else {

      return "Send_balance_success";
    }
  }
  // make setter
  public function setToken($token)
  {
    $this->name = $token;
  }
  // check token
  public function checkToken()
  {
    $data = [
      "variables" => [],
      "query" => "{
        me {
        ...FRAGMENT_COMPLETE_USER_DATA
        violations {
        __typename
        }
        __typename
        }
        }
        
        fragment FRAGMENT_COMPLETE_USER_DATA on User {
        id
        login
        userRoles
        messages
        lastActivity
        avatar
        likes
        friendsIds
        token {
        token
        __typename
        }
        favouriteAchievements {
        id
        key
        __typename
        }
        wallets {
        ...FRAGMENT_USER_WALLET
        __typename
        }
        ...FRAGMENT_USER_LOYALTY_LVL
        ...FRAGMENT_USER_PRIVACY_SETTINGS
        ...FRAGMENT_PRIVATE_USER_DATA
        __typename
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
        
        fragment FRAGMENT_USER_PRIVACY_SETTINGS on User {
        privacySettings {
        isPMNotificationsEnabled
        isWageredHidden
        isAnonymous
        __typename
        }
        __typename
        }
        
        fragment FRAGMENT_USER_LOYALTY_LVL on User {
        loyaltyLevel {
        level {
        ...LOYALTY_LEVEL
        __typename
        }
        progress
        isTemporary
        endsIn
        __typename
        }
        __typename
        }
        
        fragment LOYALTY_LEVEL on LoyaltyLevel {
        category
        level
        id
        wagerRequiredUsd
        features {
        feature
        value
        __typename
        }
        __typename
        }
        
        fragment FRAGMENT_PRIVATE_USER_DATA on User {
        email
        confirmed
        unconfirmedEmail
        protected
        serverSeed
        serverSeedNonce
        serverSeedNext
        twoFactorResetDate
        twoFactorEnabled
        friendsIds
        clientSeed
        isLongTimeInactive
        token {
        token
        __typename
        }
        violations {
        ...FRAGMENT_MY_VIOLATIONS
        __typename
        }
        __typename
        }
        
        fragment FRAGMENT_MY_VIOLATIONS on UserViolations {
        isMultiAcc
        constantAffiliateCommissionRestriction
        __typename
        }
        "
    ];
    // get withdraw from array json decode
    $checkToken = $this->http_request($data);
    if ($checkToken['data']['me'] == null) {
      return false;
    } else {
      return true;
    }
  }
  // play
  function play($amount, $chance, $bawah_atas = 1, $type)
  {
    if ($bawah_atas == 1) {
      $bawah_atas = "ABOVE";
    } else {
      $bawah_atas = "BELOW";
    }
    $data = [
      'operationName' => 'rollDice',
      'variables' => [
        'betAmount' => $amount,
        'number' => $chance,
        'side' => $bawah_atas,
        'currency' => $type
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
    // send http_request
    return $this->http_request($data);
  }
  // get balance 
  public function getBalancePRDC()
  {
    // getMe
    $me = $this->getMe();
    // get balance
    $balance = $me['data']['me']['wallets'][5]['balance'];
    return $balance;
  }

  function inVault($amount, $type)
  {
    $data = [
      "operationName" => "putMoneyInSafe",
      "variables" => [
        "amount" => $amount,
        "currency" => $type
      ],
      "query" => 'mutation putMoneyInSafe($amount: Float!, $currency: CurrencyEnum!) {
      putMoneyInSafe(amount: $amount, currency: $currency) {
        id
        wallets {
          currency
          balance
          safeAmount
          __typename
        }
        __typename
      }
    }
    '
    ];
    // send http_request
    return $this->http_request($data);
  }

  function outVault($amount, $type)
  {
    $data = [
      "operationName" => "takeMoneyFromTheSafe",
      "variables" => [
        "amount" => $amount,
        "currency" => $type
      ],
      "query" => 'mutation takeMoneyFromTheSafe($amount: Float!, $currency: CurrencyEnum!) {
     takeMoneyFromTheSafe(amount: $amount, currency: $currency) {
       id
       wallets {
         currency
         balance
         safeAmount
         __typename
       }
       __typename
     }
   }
   '
    ];
    // send http_request
    return $this->http_request($data);
  }
}

//randchance
function a_to_b($a, $b)
{
  $a = $a * 100;
  $b = $b * 100;
  $c = rand($a, $b);
  $c = $c / 100;
  return $c;
}

//randposisi
function m_to_o($m, $o)
{
  $m = $m;
  $o = $o;
  $p = rand($m, $o);
  $p = $p;
  return $p;
}

function to_satoshi($b)
{
  return number_format($b, 8, ".", "");
}


// $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJwYXJhZGljZS5pbiIsImF1ZCI6InBhcmFkaWNlLmluIiwiaWF0IjoxNjY1ODkyMDczLCJuYmYiOjE2NjU4OTIwNzMsImRhdGEiOnsiaWQiOiIxMzUzMDciLCJsb2dpbiI6IkJhbmdvY2lsIiwia2V5IjoiQ3pnRmNUVDBDa0MyNzl1SVc5SnA4YlBPcU53N3pQN0kifX0.WA3McEDZzxU-LP-F7qzzO4RT-dDnceD3uPUlBM5A_4CbFyJvrvgNk9QXIaJfK_ySC8BABM1tVNVRuLhAzHiFEZI6wThy4HMytCbPNACqV9yn51GvhEfWL8FO4o4Y5C8kw_Z8QZ3zdHmxEtS4EDqbeWiAZ5dS1X6V_21YJTZGLhcqfR4suaY2S7WDvp9JkqNuPbwVaEhf4uS_7YHBQAzkLok0iywo8x1cjaQk3MenyeATSWYHOT_vhFwyUI_HYW5tDBMh0H2-bo6n2tM-KIINbZSxvI0BFXPjzlPAUCvVd3Qxb29sjkwQmjshLTrct4fo7g-NQr4yJdiyhIXzF4uGjQ";

// $api = new para($token);
// //check token
// if ($api->checkToken() == true) {
//   echo "Token is valid";
// } else {
//   echo "Token is invalid";
// }