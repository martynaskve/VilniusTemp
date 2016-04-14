<?php

namespace mar\vilniusTempBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use mar\vilniusTempBundle\Entity\Weather;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
       //$temp =  new Weather();
        $temp= $this->getWeather();
        return $this->render('marvilniusTempBundle:Default:index.html.twig', array(
            'temp' => $temp->getTemp(),
        ));
    }

    public function getWeather()
    {   $w= 479616;
        //http://weather.yahooapis.com/forecastrss?w=479616&u=c
        //select item.condition from weather.forecast where woeid = 479616 and u='c'
        $temp= new Weather();
        $BASE_URL = "http://query.yahooapis.com/v1/public/yql";
        $yql_query = 'select item.condition from weather.forecast where woeid = 479616 and u="c"';
        $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json";
        // Make call with cURL
        $session = curl_init($yql_query_url);
        curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
        $json = curl_exec($session);
        // Convert JSON to PHP object
        $phpObj =  json_decode($json);
      //  var_dump($phpObj->query->results->channel->item->condition->temp);
        $temp->setTemp($phpObj->query->results->channel->item->condition->temp);
        //var_dump($temp);
        return $temp;//$temp;
    }
}
