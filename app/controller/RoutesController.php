<?php

declare(strict_types=1);

/*
 * Pages:
 * 1 - pocetna
 * 2 - kategorije
 * 3 - kontakt
 * 4 - privatnost
 * 5 - uvjeti
 * 6 - ugovor
 * 7 - kolacici
 * 8 - o nama
 */
class RoutesController
{
    public function index($page): void
    {
        switch ($page) {
            case 2:
            case 1:
                header( 'Location:'.App::config('url').'Trgovina/');
                break;
            case 3:
                header( 'Location:'.App::config('url').'Trgovina/kontakt');
                break;
            case 4:
                header( 'Location:'.App::config('url').'Trgovina/privatnost');
                break;
            case 5:
                header( 'Location:'.App::config('url').'Trgovina/uvjeti');
                break;
            case 6:
                header( 'Location:'.App::config('url').'Trgovina/informacije');
                break;
            case 7:
                header( 'Location:'.App::config('url').'Trgovina/kolacici');
                break;
            case 8:
                header( 'Location:'.App::config('url').'Trgovina/oNama');
                break;
            default:
                header( 'Location:'.App::config('url').'Index/');
                break;
        }
    }
}