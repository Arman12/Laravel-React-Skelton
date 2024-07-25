<?php

namespace App\Services;
use App\Traits\FormatsResponse;
class Data8Service
{

  use FormatsResponse;
  /**
   * Returns mobile number validations to check mobile is valid or not.
   * @param  string  $number
   * @return string $response
   * Author: Arman Saleem
   * Date: 31 Aug, 2023
   */
  public function verifyMobileNumber(string $number): string
  {
    $options = array(
      "Option" => array(
        array(
          "Name" => "DefaultCountryCode",
          "Value" => "44"
        ),
        array(
          "Name" => "AllowedPrefixes",
          "Value" => ""
        ),
        array(
          "Name" => "BarredPrefixes",
          "Value" => ""
        )
      )
    );

    $params = array(
      "username" => env('DATA8_USERNAME'),
      "password" => env('DATA8_PASSWORD'),
      "number" => $number,
      "options" => $options
    );
    $client = new \SoapClient(env('DATA8_SOAP_URL'));
    $result = $client->IsValid($params);
    if ($result->IsValidResult->Status->Success == 0) {
      $response  = array("success" => false, "isValid" => false, "type" => "", "message" => $result->IsValidResult->Status->ErrorMessage);
    } else {
      if ($result->IsValidResult->Result == 'Success') {

        // $response = $this->successResponse('Mobile number is valid');

        $response  = array("success" => true, "isValid" => true, "type" => "mobile", "message" => "Mobile number is valid");
      } else {
        // $response = $this->errorResponse('Mobile number is not valid');
        $response  = array("success" => true, "isValid" => false, "type" => "mobile", "message" => "Mobile number is not valid");
      }
    }
    return json_encode($response);
  }

  /**
   * Returns the validation for the given number, this will be either mobile number validation or landline number validation. If pass $isMobile true then this service will validate landline and mobile both.
   * @param  string  $number
   * @param  bool    $isMobile for mobile validation
   * @return string $response
   * Author: Arman Saleem
   * Date: 31 Aug, 2023
   */
  public function verifyNumber(string $number, bool $isMobile): string
  {
    if (strpos($number, '07') !== false) {
      $type = 'mobile';
    } else {
      $type = 'landline';
    }

    $options = array(
      "Option" => array(
        array(
          "Name" => "IgnoreExtraDigits",
          "Value" => "false"
        ),
        array(
          "Name" => "UseMobileValidation",
          "Value" => $isMobile
        )
      )
    );

    $params = array(
      "username" => env('DATA8_USERNAME'),
      "password" => env('DATA8_PASSWORD'),
      "number" => $number,
      "options" => $options
    );
    $client = new \SoapClient(env('DATA8_SOAP_URL_LANDLINE'));
    $result = $client->IsValid($params);
    if ($result->IsValidResult->Status->Success == 0) {
      $response  = array("success" => false, "isValid" => false, "type" => "", "message" => $result->IsValidResult->Status->ErrorMessage);
    } else {
      if ($result->IsValidResult->Result == 'Valid') {
        $response  = array("success" => true, "isValid" => true, "type" => $type, "message" => "$type number is valid");
      } else {
        $response  = array("success" => true, "isValid" => false, "type" => $type, "message" => "$type number is not valid");
      }
    }
    return json_encode($response);
  }


  /**
   * Returns formatted addresses against the postcode provided
   * @param  string  $postcode
   * @return string $response
   * Author: Arman Saleem
   * Date: 31 Aug, 2023
   */
  function getFullAddress(string $postcode): string
  {
    $options = array(
      "Option" => array(
        array(
          "Name" => "ReturnResultCount",
          "Value" => "true"
        ),
        array(
          "Name" => "MaxLines",
          "Value" => "6"
        ),
        array(
          "Name" => "FixTownCounty",
          "Value" => "true"
        ),
        array(
          "Name" => "IncludeLocation",
          "Value" => "true"
        )
      )
    );
    $params = array(
      "username" => env('DATA8_USERNAME'),
      "password" => env('DATA8_PASSWORD'),
      "licence" => env('DATA8_LICENSE'),
      "postcode" => $postcode,
      "building" => "",
      "options" => $options
    );
    $client = new \SoapClient(env("DATA8_ADDRESS_SOAP_URL"));
    $result = $client->GetFullAddress($params);

    if ($result->GetFullAddressResult->Status->Success == 0) {
      $response  = array("success" => false, "message" => $result->GetFullAddressResult->Status->ErrorMessage);
    } else {

      $formattedAddresses = array();
      if (is_array($result->GetFullAddressResult->Results->FormattedAddress)) {
        foreach ($result->GetFullAddressResult->Results->FormattedAddress as $item) {
          $linesArray = $item->Address->Lines->string;
          $rawAddress = $item->RawAddress;
          $formattedArray = [
            'Address' => [
              'Lines' => $linesArray,
            ],
            'RawAddress' => $rawAddress,
          ];
          $formattedAddresses[] = $formattedArray;
        }
      } else {
        $item = $result->GetFullAddressResult->Results->FormattedAddress;
        $linesArray = $item->Address->Lines->string;
        $rawAddress = $item->RawAddress;
        $formattedArray = [
          'Address' => [
            'Lines' => $linesArray,
          ],
          'RawAddress' => $rawAddress,
        ];
        $formattedAddresses[] = $formattedArray;
      }

      $response = array("success" => true, "message" => "Address retrieved successfully", "data" => $formattedAddresses);
    }

    return (json_encode($response));
  }


  /**
   * Returns the email domain validation to verify email is valid or not
   * @param  string  $email
   * @return string $response
   * Author: Arman Saleem
   * Date: 31 Aug, 2023
   */
  function emailDomainVerification(string $email): string
  {
    $options = array(
      "Option" => array(
        array(
          "Name" => "MissingMXRecordHandling",
          "Value" => "ServerCheck"
        )
      )
    );

    $level = env('DATA8_EMAIL_LEVEL');

    $params = array(
      "username" => env('DATA8_USERNAME'),
      "password" => env('DATA8_PASSWORD'),
      "licence" => env('DATA8_LICENSE'),
      "email" => $email,
      "level" => $level,
      "options" => $options
    );
    $client = new \SoapClient(env("DATA8_EMAIL_SOAP_URL"));
    $result = $client->IsValid($params);
    if ($result->IsValidResult->Status->Success == 0) {
      $response  = array("success" => false, "isValid" => false, "type" => "", "message" => $result->IsValidResult->Status->ErrorMessage);
    } else {
      if ($result->IsValidResult->Result == 'Valid') {
        $response  = array("success" => true, "isValid" => true, "type" => 'email', "message" => "email is valid");
      } else {
        $response  = array("success" => true, "isValid" => false, "type" => 'email', "message" => "email is not valid");
      }
    }

    return (json_encode($response));
  }
}
