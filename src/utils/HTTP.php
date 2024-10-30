<?php

namespace MDDD;

class HTTP {
  public static function create_request_headers($username, $password) {
    return array(
      'Content-Transfer-Encoding' => 'application/json',
      'Authorization' => 'Basic '. base64_encode($username . ':' . $password)
    );
  }
  
  public static function generate_accessclient_token($base_url, $accesscode, $username, $password) {
    $url = "{$base_url}/clients/activate?code={$accesscode}";
    $headers = self::create_request_headers($username, $password);
    
    $response = wp_remote_request( $url, array(
        'method'      => 'POST',
        'timeout'     => 45,
        'redirection' => 15,
        'sslverify'   => false,
        'blocking'    => true,
        'headers'     => $headers,
        'body'        => array(),
      )
    );
  
    $response_code = wp_remote_retrieve_response_code($response);
  
    switch($response_code) {
      case 200:
        \WC_Admin_Settings::add_message(__('AccessClient has successfully been activated.', 'circulair-geld'));
        $response_body = wp_remote_retrieve_body($response);
        $json = json_decode($response_body);
  
        return $json->token;
      case 401:
        \WC_Admin_Settings::add_error(__('Error: Wrong username and/or password.', 'circulair-geld'));
        return null;
      case 404:
        \WC_Admin_Settings::add_error(__('Error: Your submission is missing data, is your activation code valid?', 'circulair-geld'));
        return null;
      default:
        \WC_Admin_Settings::add_error(__('Error: an unknown error has occurred.', 'circulair-geld'));
        return null;
    }
  }
  
  public static function test_user_credentials($base_url, $username, $password) {
    $url = "{$base_url}/auth/session";
    $headers = self::create_request_headers($username, $password);
    
    $response = wp_remote_request( $url, array(
        'method'      => 'POST',
        'timeout'     => 45,
        'redirection' => 15,
        'sslverify'   => false,
        'blocking'    => true,
        'headers'     => $headers,
        'body'        => array(),
      )
    );
  
    $response_code = wp_remote_retrieve_response_code($response);
  
    switch ($response_code) {
      case 200:
        \WC_Admin_Settings::add_message(__('Your credentials have ben validated.', 'circulair-geld'));
        break;
      case 401:
        \WC_Admin_Settings::add_error(__('Your credentials are incorrect.', 'circulair-geld'));
        break;
      default:
        \WC_Admin_Settings::add_error(__('An unknown error has occurred.', 'circulair-geld'));
        break;
    }
  
  }
  
  public static function generate_ticket_number($base_url, $headers, $body) {
    $url = "{$base_url}/tickets";
  
    $response = wp_remote_request( $url, array(
        'method'      => 'POST',
        'timeout'     => 45,
        'redirection' => 15,
        'sslverify'   => false,
        'blocking'    => true,
        'headers'     => $headers,
        'body'        => json_encode($body),
      )
    );
  
    if ( is_wp_error($response) ) {
        return __('Error: ', 'circulair-geld') . $response->get_error_message();
    } else {
      $response_body = wp_remote_retrieve_body($response);
      $json = json_decode($response_body);
  
      $response_code = wp_remote_retrieve_response_code($response);
  
      switch ($response_code) {
          case 201:
          // succes
          return $json->ticketNumber;
          default:
          return __('Error: unknown responsecode: %s', 'circulair-geld') . $response_code;
          break;
      }
    }
  }
  
  public static function process_ticket($base_url, $headers, $ticketNumber, $orderId) {
    $url = "$base_url/tickets/$ticketNumber/process?orderId=$orderId";
    $response = wp_remote_request( $url, array(
        'method'      => 'POST',
        'timeout'     => 45,
        'redirection' => 15,
        'sslverify'   => false,
        'blocking'    => true,
        'headers'     => $headers,
        'body'        => array(),
        )
    );
  
    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);
    $json = json_decode($response_body);
    $error = null;
    
    switch ($response_code) {
        case 200:
            if ($json->actuallyProcessed) {
                $tx = $json->transaction;
                // Using the transaction number is preferred.
                // But in case it is disabled in Cyclos, return the internal identifier.
                return empty($tx->transactionNumber) ? $tx->id : $tx->transactionNumber; 
            }
            return NULL;
        case 401:
            $error = __('No credentials', 'circulair-geld');
            break;
        case 403:
            $error = __('Access denied', 'circulair-geld');
            break;
        case 404:
            $error = __('Ticket not found', 'circulair-geld');
            break;
        case 422:
            $error = __('Invalid ticket', 'circulair-geld');
            break;
        case 500:
            // An error has occurred generating the payment
            if ($json->code == 'insufficientBalance') {
                $error = __('Not enough balance.', 'circulair-geld');
                break;
            } else if ($json->code == 'destinationUpperLimitReached') {
                $error = __('Maximum credit limit reached.', 'circulair-geld');
                break;
            } else {
                // There are more error codes but for now only these two
                // Log a detailed error
                error_log("An unexpected error has occurred processing the ticket (type = {$json->exceptionType}, message = {$json->exceptionMessage})");
            }
        default:
            $error = __('An unknown error has occurred: %s', 'circulair-geld') . $response_code;
            break;
    }
    
    // There was an error
    throw new Exception($error);
  }
}
