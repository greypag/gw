<?php
/* Our class name should follow the directory structure of our Observer.php model, starting from the namespace, replacing directory separators with underscores. The directory of ousr Observer.php is following:
 app/code/local/Mage/ProductLogUpdate/Model/Observer.php */
class Mage_Apsis_Model_NewsLetter
{
    public function getApsisSubscriberId(){
        $email = Mage::getSingleton('customer/session')->getCustomer()->getEmail();
        $service_url = 'http://95fe752b-8162-402d-a503-cdb5b23912d7:@se.api.anpasia.com/subscribers/v2/email';
        $curl = curl_init($service_url);
        $header[] = "Accept: application/json";
        $header[] = "Content-Type:application/json";

        curl_setopt($curl, CURLOPT_HTTPHEADER, $header );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($email));

        $curl_response = curl_exec($curl);

        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('error occured during curl exec. Additioanl info: ' . var_export($info));
        }

        curl_close($curl);
        $decoded = json_decode($curl_response);

        if($decoded->Code == '1'){
            return $decoded->Result;
        }
        else{
            return '';
        }
    }

    public function getApsisIsSubscribed(){
        $mailinglistId = '168822';
        $subscriberId = $this->getApsisSubscriberId();

        if($subscriberId == ''){
          return false;
        }
        else{
            $service_url = "http://95fe752b-8162-402d-a503-cdb5b23912d7:@se.api.anpasia.com/v1/subscribers/$subscriberId/mailinglists";
            $curl = curl_init($service_url);
            $header[] = "Accept: application/json";
            $header[] = "Content-Type:application/json";

            curl_setopt($curl, CURLOPT_HTTPHEADER, $header );
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_GET, true);

            $curl_response = curl_exec($curl);

            if ($curl_response === false) {
                $info = curl_getinfo($curl);
                curl_close($curl);
                die('error occured during curl exec. Additioanl info: ' . var_export($info));
            }

            curl_close($curl);
            $decoded = json_decode($curl_response);

            if($decoded->Code == '1'){
                foreach($decoded->Result->Mailinglists as $item) {
                    if($mailinglistId == $item->Id){
                        return true;
                    }
                }
            }
            return false;
        }
    }

    public function addtoApsisMailinglist($email, $name){
        $mailinglistId = '168822';
        $service_url = "http://95fe752b-8162-402d-a503-cdb5b23912d7:@se.api.anpasia.com/v1/subscribers/mailinglist/$mailinglistId/create?updateIfExists=true";
        $curl = curl_init($service_url);
        $header[] = "Accept: application/json";
        $header[] = "Content-Type:application/json";

        curl_setopt($curl, CURLOPT_HTTPHEADER, $header );

        $curl_post_data = array(
            'CountryCode' => '',
            'DemDataFields' => [],
            'Email' => $email,
            'Format' => 'HTML',
            'Name' => $name,
            'Password' => '',
            'PhoneNumber' => ''
        );

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curl_post_data));

        $curl_response = curl_exec($curl);

        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('error occured during curl exec. Additioanl info: ' . var_export($info));
        }

        curl_close($curl);
    }

    function removeFormApsisMailinglist($subscriberId){
      $mailinglistId = '168822';
      $service_url = "http://95fe752b-8162-402d-a503-cdb5b23912d7:@se.api.anpasia.com/v1/mailinglists/$mailinglistId/subscriptions/$subscriberId";
      $curl = curl_init($service_url);
      $header[] = "Accept: application/json";
      $header[] = "Content-Type:application/json";

      curl_setopt($curl, CURLOPT_HTTPHEADER, $header );
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');

      $curl_response = curl_exec($curl);

      if ($curl_response === false) {
          $info = curl_getinfo($curl);
          curl_close($curl);
          die('error occured during curl exec. Additioanl info: ' . var_export($info));
      }

      curl_close($curl);
    }
}
?>
