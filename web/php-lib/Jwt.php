<?php

class Jwt {
    private string $secret;
    private array $algorithms = ['HS256' => 'sha256', 'HS384' => 'sha384', 'HS512' => 'sha512'];
    private array $headerVals = ['alg' => 'HS256', 'typ' => 'JWT'];
    
    public $payloadStd;

    public function __construct(string $secret, string $algo) {
        
        if ($secret == '' || $algo == '') { 
            throw new InvalidArgumentException("Error in received data.");
        }

        $this->secret = $secret;
        $this->algo = $algo;
    }
    
    // encode and decode from
    // https://www.php.net/manual/ru/function.base64-encode.php#103849
    public static function encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function decode($data) {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    public function buildToken(array $payloadVals) {

        if (array_key_exists($this->algo, $this->algorithms)) {
            $this->headerVals['alg'] = $this->algo;
        } else {
            throw new InvalidArgumentException("Error: algorithm $this->algo not supported.");
        }

        $header = json_encode($this->headerVals);
        $payload = json_encode($payloadVals);
        $b64h = self::encode($header);
        $b64pl = self::encode($payload);
        $signature = hash_hmac($this->algorithms[$this->algo], $b64h.$b64pl, $this->secret);

        return sprintf('%s.%s.%s', $b64h, $b64pl, $signature);
    }

    public function analize(string $token) {
        list($h, $pl, $s) = explode('.', $token);
        
        // signature analysis
        if (!hash_equals($s, hash_hmac($this->algorithms[$this->algo], $h.$pl, $this->secret))) {
            return false;
        } 
        // if signatures matched
        $this->payloadStd = self::decode($pl);

        if (isset($this->payloadStd->exp) && (time() > $this->payloadStd->exp)) {
            return false;
        }
        
        if (isset($this->payloadStd->iat) && (time() < $this->payloadStd->iat)) {
            return false;
        }

        return true;
    }

    
}
