<?php

/**
 * Klasa pomocnicza do integracji z aplikacją Adio, służącą do wymiany SMSów.
 *
 * @author Darek Skrzypczak <kontakt@app4you.pl>
 *
 */
class AdioSmsSupport
{

    const ADIO_SIGNATURE = '3vd5op8pn3vzmvbrkuujnoidxn8fe617';

    /**
     * Przygotowuje podpis dla przesyłanych danych.
     *
     *
     * Żądania należy podpisywać. Podpis to hash md5 wyliczony ze złączenia parametrów
     * żądania oraz tajnego podpisu firmy wygenerowanego w aplikacji Panoramix.
     *
     * Podpis firmy: 3vd5op8pn3vzmvbrkuujnoidxn8fe617
     *
     * Na przykład jeśli żądanie zawiera parametr "prefix" o wartości "IZYLDA", to
     * podpis będzie następujący:
     *
     * md5("IZYLDA3vd5op8pn3vzmvbrkuujnoidxn8fe617") = e03b79fb1db6ffa1bb42c53f0bf82bcd
     *
     * @param array $i_Data
     * @author Darek Skrzypczak <kontakt@app4you.pl>
     */
    public static function prepareSignature($i_Data)
    {
        $o_Signature = '';

        foreach( $i_Data as $_paramValue)
        {
            $o_Signature .= $_paramValue;
        }

        $o_Signature = md5($o_Signature . self::ADIO_SIGNATURE);

        return $o_Signature;
    }

    /**
     * Dodaje usera dla danego prefixu.
     *
     * @param string $i_Prefix Unikalny login klienta.
     * @author Darek Skrzypczak <kontakt@app4you.pl>
     * @return array Tablica z odpowiedzią z serwera.
     */
    public static function addUser($i_Prefix)
    {
        $_data = array();
        $_data['prefix'] = $i_Prefix;
        $_data['signature'] = self::prepareSignature($_data);

        return json_decode(self::sendCurl('http://public.panoramix.fm/apps/adio/user_add/', array('data' => json_encode($_data))), true);
    }

    /**
     * Usuwa usera z danej akcji sms.
     *
     * @param int $i_SmsEventId Unikalny ID akcji SMS.
     * @author Darek Skrzypczak <kontakt@app4you.pl>
     * @return array Tablica z odpowiedzią z serwera.
     */
    public static function deleteUser($i_SmsEventId)
    {
        $_data = array();
        $_data['event_id'] = $i_SmsEventId;
        $_data['signature'] = self::prepareSignature($_data);

        return json_decode(self::sendCurl('http://public.panoramix.fm/apps/adio/user_delete/', array('data' => json_encode($_data))), true);
    }

    /**
     * Wysyła zapytanie za pomocą curla.
     *
     * @param string $i_Url
     * @param array $i_Data
     * @author Darek Skrzypczak <kontakt@app4you.pl>
     */
    public static function sendCurl($i_Url, $i_Data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $headers[] = 'Content-type: application/x-www-form-urlencoded;';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $user_agent = "Mozilla/5.0 (Windows; U; Windows NT 6.0; pl; rv:1.8.1.16) Gecko/20080702 Firefox/2.0.0.16";
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

        if(!empty($i_Data)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            $postFields = array();
            foreach( $i_Data as $key => $data )
            {
                // gdy jest wartosc jest tablica, inaczej ja dopisujemy
                if ( is_array($data) )
                {
                    foreach( $data as $_dataIndex => $_dataVal )
                    {
                        $postFields[] = $key . '[' . $_dataIndex . ']=' . $_dataVal;
                    }
                }
                else
                {
                    $postFields[] = $key . '=' .$data;
                }
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, implode("&",$postFields));
        }
        curl_setopt($ch, CURLOPT_URL, $i_Url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $o_Response = curl_exec($ch);
        if( true === curl_errno($ch))
        {
            echo 'Curl error: ' . curl_error($ch);
        }

        curl_close($ch);

        return $o_Response;
    }
}