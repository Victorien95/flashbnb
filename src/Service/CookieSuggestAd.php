<?php


namespace App\Service;


use App\Entity\Ad;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class CookieSuggestAd
{
    private $ad;
    /**
     * @var Request
     */
    private $request;


    /**
     * @return Ad
     */
    public function getAd(): Ad
    {
        return $this->ad;
    }

    /**
     * @param Ad $ad
     * @return CookieSuggestAd
     */
    public function setAd(Ad $ad): CookieSuggestAd
    {
        $this->ad = $ad;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $request
     * @return CookieSuggestAd
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    public function CookieSuggestSet($request, $ad, $maxSearch = 5)
    {
        $response = new Response();
        $this->setAd($ad);
        $this->setRequest($request);

        if (!$this->request){
            throw new \Exception("Vous n'avez pas spécifié la request");
        }

        if (!$this->ad){
            throw new \Exception("Vous n'avez pas spécifié l'entité d'annonce");
        }

        $this->SuggestCookieInit($request);

        $array = $this->SuggestArrayPusher($maxSearch);
        //dump($array);
        if ($array !== false){
            $cookie = new Cookie('suggest',  serialize($array), time() + ( 2 * 365 * 24 * 60 * 60));
            //dump('SuggestWork STTTAAAAAAAAAAAAAAAAAART');
            //dump(serialize($array));
            $response->headers->setCookie($cookie);
            $response->send();
            //dump($response->headers->getCookies());
            //dump($this->request->cookies->get('suggest'));

        }
    }


    // Initialisation ou récupération des cookie
    private function SuggestCookieInit($request)
    {
        //dump('SuggestCookieInit STAAAAAAAAAAAAAAAAAAART');
        $data = [];
        $this->setRequest($request);

        if ($this->request->cookies->get('suggest')){
            //dump('EXIST');
            return;
        }else{
            //dump('DONT EXIST');
            $this->request->cookies->set('suggest', serialize($data));
        }
    }

    private function SuggestArrayPusher($maxSearch)
    {
        //dump('SuggestArrayPusher STAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAART');

        $array = unserialize($this->request->cookies->get('suggest'));
        //$array = $this->request->cookies->get('suggest');
        //dump($array);
        $arrayCount = count($array);

        if ($arrayCount > 0 && $arrayCount < $maxSearch){
            //dump('SuggestArrayPusher $arrayCount >= 0 && $arrayCount < 10');
            return $this->ArrayPusher($arrayCount, $array);
        }
        if ($arrayCount >= $maxSearch){
            //dump('SuggestArrayPusher $arrayCount >= 10');
            while ($arrayCount > $maxSearch - 1){
                array_shift($array);
                $arrayCount = count($array);
            }
        }
        return $this->ArrayPusher($arrayCount, $array);

    }

    private function ArrayPusher($arrayCount, $array)
    {
        $ad = $this->getAd();
        //dump('ArrayPusher STTTAAAAAAAAAAAART');

        //dump($array);
        if ($arrayCount === 0){
            //dump('ArrayPusher === 0');

            $array[] = [$ad->getId(), $ad->getPrice(), $ad->getRooms()];
            //dump($array);

            return $array;
        }else{
            //dump(' ArrayPusher Boucle');

            foreach ($array as $key => $value){
                if ($ad->getId() === $value[0]){
                    //dump($value[0]);
                    //dump('CEST EGAL');
                    return false;
                }
            }
            //dump('CEST EGAL donc ca doit pas passer');

            $array[] = [$ad->getId(), $ad->getPrice(), $ad->getRooms()];
        }
        return $array;
    }

    public function CookieRemove()
    {
        $reponse = new Response();

        $reponse->headers->clearCookie('suggest');
        $reponse->send();
    }
}