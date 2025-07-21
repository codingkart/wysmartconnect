<?php



/**

 * @class    fetch_ftp_data

 * @category FTP and Excel Integration for WooCommerce

 * @author   CodingKart

 */



// require_once __DIR__ . '/PhpSpreadsheet/vendor/autoload.php';



// use PhpOffice\PhpSpreadsheet\Reader\Xlsx;



class fetch_ftp_data

{

    protected static $_instance = null;



    public static function get_instance()

    {

        if (is_null(self::$_instance)) {

            self::$_instance = new self();

        }

        return self::$_instance;

    }



    public function __construct()

    {

        $this->hooks();

    }



    public function hooks() {}





    /**

     * Process FTP and Excel data to find the tracking code for the given ASIN.

     */



    public function process_ftp_and_excel($asin_number, $manufacturer_id)

    {

        global $wpdb;



        // Get the FTP configuration details

        $table_name = $wpdb->prefix . 'manufacturer_ftp';

        $data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_name} WHERE id = %d", $manufacturer_id), ARRAY_A);



        if (!$data) {

            return [

                "success" => false,

                "message" => "Manufacturer FTP data not found.",

            ];

        }

        // Determine secure flag based on FTP username

        $secure = strpos($data['ftp_server'], 'ftps') === 0;

        // Prepare FTP details

        $ftpDetails = [

            "host" => $data['ftp_server'],

            "user" => $data['ftp_username'],

            "password" => $data['ftp_password'],

            "filePath" => "/asn/ASN{$asin_number}.csv",

            "port" => isset($data['port']) ? intval($data['port']) : 21, // Default port

            "secure" => $secure,

        ];



        // Get the endpoint URL dynamically from WordPress options

        $endpoint = get_option('ftp_url_endpoint');

        if (empty($endpoint) || !filter_var($endpoint, FILTER_VALIDATE_URL)) {

            return [

                "success" => false,

                "message" => "Invalid or missing FTP endpoint URL in options.",

            ];

        }



        // Call the FTP interaction method

        return $this->interactWithFtpEndpoint($endpoint, $ftpDetails);

    }



    public function interactWithFtpEndpoint($endpoint, $ftpDetails)

    {

        // Initialize cURL

        $curl = curl_init();



        curl_setopt_array($curl, [

            CURLOPT_URL => $endpoint,

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_TIMEOUT => 60,

            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

            CURLOPT_CUSTOMREQUEST => 'POST',

            CURLOPT_POSTFIELDS => json_encode($ftpDetails),

            CURLOPT_HTTPHEADER => [

                'Content-Type: application/json',

            ],

        ]);



        // Execute cURL request

        $response = curl_exec($curl);



        // Check for cURL errors

        if (curl_errno($curl)) {

            $error_msg = curl_error($curl);

            curl_close($curl);

            return [

                "success" => false,

                "message" => "cURL Error: {$error_msg}",

            ];

        }



        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);



        // Check HTTP status

        if ($http_status !== 200) {

            return [

                "success" => false,

                "message" => "HTTP Error: {$http_status}",

                "raw_response" => $response,

            ];

        }



        // Decode the JSON response

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {

            return [

                "success" => false,

                "message" => "Invalid JSON response: " . json_last_error_msg(),

            ];

        }



        // Extract Tracking Numbers

        $trackingNumbers = array_column($data, 'TrackingNumber');

        

        return [

            "success" => true,

            "trackingNumbers" => $trackingNumbers,

            "data" => $data,

        ];

    }



    /**

     * check ftp connection user save ftp in admin side

     */



    // public function checkFTPConnection($ftp_server, $ftp_username, $ftp_password, $file_path, $port, $secure)

    // {

    //     $curl = curl_init();



    //     $postData = json_encode([

    //         "host" => $ftp_server,

    //         "user" => $ftp_username,

    //         "password" => $ftp_password,

    //         "filePath" => $file_path,

    //         "port" => $port,

    //         "secure" => $secure,

    //     ]);



    //     curl_setopt_array($curl, [

    //         CURLOPT_URL => get_option('ftp_url_endpoint'),

    //         CURLOPT_RETURNTRANSFER => true,

    //         CURLOPT_ENCODING => '',

    //         CURLOPT_MAXREDIRS => 10,

    //         CURLOPT_TIMEOUT => 60,

    //         CURLOPT_FOLLOWLOCATION => true,

    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

    //         CURLOPT_CUSTOMREQUEST => 'POST',

    //         CURLOPT_POSTFIELDS => $postData,

    //         CURLOPT_HTTPHEADER => [

    //             'Content-Type: application/json',

    //         ],

    //         CURLOPT_VERBOSE => true,

    //     ]);



    //     $verbose = fopen('php://temp', 'w+');

    //     curl_setopt($curl, CURLOPT_STDERR, $verbose);



    //     $response = curl_exec($curl);



    //     if (curl_errno($curl)) {

    //         $error_msg = curl_error($curl);

    //         curl_close($curl);

    //         return [

    //             "success" => false,

    //             "message" => "cURL Error: $error_msg",

    //         ];

    //     }



    //     $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    //     rewind($verbose);

    //     $verbose_log = stream_get_contents($verbose);

    //     fclose($verbose);



    //     error_log("cURL Debug Log: " . $verbose_log);

    //     error_log("HTTP Status: $http_status");



    //     curl_close($curl);



    //     if ($http_status !== 200) {

    //         return [

    //             "success" => false,

    //             "message" => "HTTP Error: $http_status",

    //             "raw_response" => $response,

    //         ];

    //     }



    //     return [

    //         "success" => true,

    //         "data" => json_decode($response, true),

    //     ];

    // }

    /**

     * check ftp connection user save ftp in admin side

     */
    public function checkFTPConnection($ftp_server, $ftp_username, $ftp_password, $port, $secure)
    {
        $ftp_api_url = "https://shipment-tracking-nodejsapp-enbcg8c2a2hhbpfu.canadacentral-01.azurewebsites.net/checkftpConnection";

        $postData = json_encode([
            "host" => $ftp_server,
            "user" => $ftp_username,
            "password" => $ftp_password,
            "port" => $port,
            "secure" => $secure,
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $ftp_api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60, // Reduced timeout for better performance
            // CURLOPT_CONNECTTIMEOUT => 5, // Faster failure on connection issues
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
        ]);

        $response = curl_exec($curl);
        $error_msg = curl_error($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($error_msg) {
            return [
                "success" => false,
                "message" => "cURL Error: $error_msg"
            ];
        }

        if ($http_status !== 200) {
            return [
                "success" => false,
                "message" => "HTTP Error: $http_status",
                "raw_response" => $response
            ];
        }

        return [
            "success" => true,
            "data" => json_decode($response, true)
        ];
    }


}



// Instantiate the class

function ck_fetch_data_object()

{

    return fetch_ftp_data::get_instance();

}

ck_fetch_data_object();

