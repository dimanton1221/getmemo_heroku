<?php
$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJwYXJhZGljZS5pbiIsImF1ZCI6InBhcmFkaWNlLmluIiwiaWF0IjoxNjY1ODkyMDczLCJuYmYiOjE2NjU4OTIwNzMsImRhdGEiOnsiaWQiOiIxMzUzMDciLCJsb2dpbiI6IkJhbmdvY2lsIiwia2V5IjoiQ3pnRmNUVDBDa0MyNzl1SVc5SnA4YlBPcU53N3pQN0kifX0.WA3McEDZzxU-LP-F7qzzO4RT-dDnceD3uPUlBM5A_4CbFyJvrvgNk9QXIaJfK_ySC8BABM1tVNVRuLhAzHiFEZI6wThy4HMytCbPNACqV9yn51GvhEfWL8FO4o4Y5C8kw_Z8QZ3zdHmxEtS4EDqbeWiAZ5dS1X6V_21YJTZGLhcqfR4suaY2S7WDvp9JkqNuPbwVaEhf4uS_7YHBQAzkLok0iywo8x1cjaQk3MenyeATSWYHOT_vhFwyUI_HYW5tDBMh0H2-bo6n2tM-KIINbZSxvI0BFXPjzlPAUCvVd3Qxb29sjkwQmjshLTrct4fo7g-NQr4yJdiyhIXzF4uGjQ";

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
 
 

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api.paradice.in/api.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$headers = array();
$headers[] = 'Authority: api.paradice.in';
$headers[] = 'Accept: */*';
$headers[] = 'Accept-Language: EN';
$headers[] = 'Content-Type: application/json';
$headers[] = 'Cookie: _gid=GA1.2.545124408.1666093415; _ga_SNE2NM1XYH=GS1.1.1666102565.5.1.1666105064.0.0.0; _ga=GA1.2.2072690788.1665891952; _gat_gtag_UA_143943777_1=1';
$headers[] = 'Origin: https://paradice.in';
$headers[] = 'Referer: https://paradice.in/';
$headers[] = 'Sec-Ch-Ua: \"Chromium\";v=\"106\", \"Google Chrome\";v=\"106\", \"Not;A=Brand\";v=\"99\"';
$headers[] = 'Sec-Ch-Ua-Mobile: ?0';
$headers[] = 'Sec-Ch-Ua-Platform: \"Windows\"';
$headers[] = 'Sec-Fetch-Dest: empty';
$headers[] = 'Sec-Fetch-Mode: cors';
$headers[] = 'Sec-Fetch-Site: same-site';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36';
$headers[] = 'X-Access-Token: '.$token;
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}else
{
    // if null
    $json = json_decode($result, true);
    if ($json['data']['me'] == null) {
        echo "token salah";
    } else {
        echo json_encode($json, JSON_PRETTY_PRINT);
    }
}
curl_close($ch);