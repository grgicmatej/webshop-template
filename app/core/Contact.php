<?php

declare(strict_types=1);

/* Class for sending emails */

use Model\OrderModel;
use Model\SettingModel;
use Model\UserDetailsModel;
use Model\UserModel;

class Contact
{
    public const NO_REPLY = 'no-reply@uncasual.design';
    public static function send(string $contactEmail): bool
    {
        if ('1' !== $_GET['c']) {
            return false;
        }

        if (true === self::isSpam()) {
            return false;
        }

        $mailHeaders = "From: <" . $_POST["email"] . ">\r\n" .
            'Reply-To:' . $_POST["email"] . "\r\n";
        $mailHeaders .= "MIME-Version: 1.0\r\n";
        $mailHeaders .= "Content-Type: text/html; charset=UTF-8\r\n";

        $email_subject = "Upit sa web shopa uncasual.design";

        $email_message ="<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
            <html xmlns='http://www.w3.org/1999/xhtml'>
            <head>
            <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
            <title>uncasual.design upit</title>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'/>
            </head>
            <body style='margin: 0; padding: 0;'>
                <table border='0' cellpadding='0' cellspacing='0' width='100%'> 
                    <tr>
                        <td style='padding: 10px 0 30px 0;'>
                            <table align='center' border='0' cellpadding='0' cellspacing='0' width='600' style='border: 0 solid #cccccc; border-collapse: collapse;'>   
                                <tr>
                                    <td bgcolor='#ffffff' style='padding: 40px 30px 40px 30px;'>
                                        <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                            <tr>
                                                <td style='color: #153643; font-family: Arial, sans-serif; font-size: 24px; text-align: center'>
                                                    <b>Novi upit sa web trgovine</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style='text-align: center; padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;'>
                                                    Ime korisnika: ".$_POST["name"]." ".$_POST["surname"]."                            
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style='text-align: center; padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;'>
                                                    Email korisnika: ".$_POST['email']."                            
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style='text-align: center; padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;'>
                                                    Poruka: ".$_POST["message"]."                            
                                                </td>
                                            </tr>
                                            <br><br>           
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor='#9680af' style='padding: 30px 30px 30px 30px;'>
                                        <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                            <tr>
                                                <td style='color: #ffffff; font-family: Arial, sans-serif; font-size: 14px; text-align: center' width='100%'>
                                                    &reg; uncasual.design " . date('Y') . "<br/>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </body>
            </html>";

        return mail($contactEmail, $email_subject, $email_message, $mailHeaders);
    }

    private static function isSpam(): bool
    {
        if (false === isset($_POST["email"]) || strlen($_POST["email"]) < 3) {
            return true;
        }

        if (false === isset($_POST["name"]) || strlen($_POST["name"]) < 2) {
            return true;
        }

        if (false === isset($_POST["surname"]) || strlen($_POST["surname"]) < 2) {
            return true;
        }

        if (false === isset($_POST["message"]) || strlen($_POST["message"]) < 2) {
            return true;
        }

        if (str_contains($_POST["message"], 'seo'))
        {
            return true;
        }

        return false;
    }

    public static function newOrderEmailForAdministrator(SettingModel $notificationSettings, UserModel $user, OrderModel $order): bool
    {
        $mailHeaders = "From: ".self::NO_REPLY . "\r\n";
        $mailHeaders .= "Reply-To: ". self::NO_REPLY . "\r\n";
        $mailHeaders .= "MIME-Version: 1.0\r\n";
        $mailHeaders .= "Content-Type: text/html; charset=UTF-8\r\n";

        $email_subject = "Nova narudžba - " .$order->getId();

        $email_message = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
            <html xmlns='http://www.w3.org/1999/xhtml'>
            <head>
            <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
        <title>uncasual.design</title>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'/>
            </head>
            <body style='margin: 0; padding: 0;'>
                <table border='0' cellpadding='0' cellspacing='0' width='100%'> 
                    <tr>
                        <td style='padding: 10px 0 30px 0;'>
                            <table align='center' border='0' cellpadding='0' cellspacing='0' width='600' style='border: 0px solid #cccccc; border-collapse: collapse;'>
                                <tr>
                                    <td bgcolor='#ffffff' style='padding: 40px 30px 40px 30px;'>
                                        <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                            <tr>
                                                <td style='color: #153643; font-family: Arial, sans-serif; font-size: 24px; text-align: center'>
                                                <b>Obavijest - nova narudžba</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style='padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;'>
                                                Poštovani, zaprimili ste novu narudžbu na web trgovini uncasual.design 
                                                </td>
                                            </tr>    
                                            <tr>
                                                <td>
                                                    <b>Detalji narudžbe:</b><br><br>
                                                    <p>Broj narudžbe: " . $order->getId() . "</p>
                                                    <p>Datum narudžbe: " . $order->getCreatedAt()->format('d.m.Y H:i:s') . "</p>
                                                    <p>Način plaćanja: " . $order->getPaymentMethod() . "</p>
                                                    <p>Iznos: " . $order->getTotalPrice() . " EUR</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style='padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;'>
                                                Za više detalja otvorite upravljačku ploču web trgovine
                                                </td>
                                            </tr>    
                                            <br><br>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                <td bgcolor='#9680af' style='padding: 30px 30px 30px 30px;'>
                                    <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                        <tr>
                                            <td style='color: #ffffff; font-family: Arial, sans-serif; font-size: 14px; text-align: center' width='100%'>
                                                    &reg; uncasual.design " . date('Y') . "<br/>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </body>
            </html>";

        return mail($notificationSettings->getValue(), $email_subject, $email_message, $mailHeaders);
    }

    public static function newOrderEmailForUser(UserModel $user, OrderModel $order, UserDetailsModel $userDetails, SettingModel $notificationSettings): bool
    {
        $mailHeaders = "From: ".self::NO_REPLY . "\r\n";
        $mailHeaders .= "Reply-To: ". self::NO_REPLY . "\r\n";
        $mailHeaders .= "MIME-Version: 1.0\r\n";
        $mailHeaders .= "Content-Type: text/html; charset=UTF-8\r\n";

        $email_subject = "Potvrda narudžbe - " .$order->getId();

        $email_message ="<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
        <html xmlns='http://www.w3.org/1999/xhtml'>
        <head>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
        <title>uncasual.design</title>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'/>
        </head>
        <body style='margin: 0; padding: 0;'>
            <table border='0' cellpadding='0' cellspacing='0' width='100%'> 
                <tr>
                    <td style='padding: 10px 0 30px 0;'>
                        <table align='center' border='0' cellpadding='0' cellspacing='0' width='600' style='border: 0px solid #cccccc; border-collapse: collapse;'>
                            <tr>
                                <td bgcolor='#ffffff' style='padding: 40px 30px 40px 30px;'>
                                    <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                        <tr>
                                            <td style='color: #153643; font-family: Arial, sans-serif; font-size: 24px; text-align: center'>
                                                <b>Zaprimljena narudžba</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style='padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;'>
                                                Poštovani, zahvaljujemo na Vašoj narudžbi. 
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Detalji narudžbe:</b><br><br>
                                                <p>Broj narudžbe: ". $order->getId()."</p>
                                                <p>Datum narudžbe: ". $order->getCreatedAt()->format('d.m.Y H:i:s')."</p>
                                                <p>Način plaćanja: ". $order->getPaymentMethod()."</p>
                                                <p>Kontakt email: " . $user->getEmail() . "</p>
                                                <p>Kontakt telefon: " . $userDetails->getPhone() . "</p>
                                                <p>Adresa za dostavu: " . $userDetails->getName() . " " . $userDetails->getSurname() . ", " . $userDetails->getAddress() . ", " . $userDetails->getPostal() . " " . $userDetails->getCity() . "</p>                                                    
                                            </td>
                                        </tr>
                                        <br><br>
                                        <tr>
                                            <td style='border: 1px solid black; padding: 20px 20px 30px 20px'>
                                                <b>Ukoliko je odabrano plaćanje uplatom na račun, podaci za uplatu su:</b><br>
                                                <p>APM, obrt za usluge i trgovinu</p>
                                                <p>Ivana Gundulića 30</p>
                                                <p>10430, Samobor<br>
                                                <p>IBAN: ". $notificationSettings->getValue()."</p>                                    
                                                <p>Iznos uplate: ". $order->getTotalPrice()." EUR</p>
                                                <p>Poziv na broj: ". $order->getId(). " ". date('Y')."</p>
                                                <p>Opis plaćanja: ". $order->getId().", ". $userDetails->getName() . " " . $userDetails->getSurname()."</p>
                                                <br><br>
                                                <p>Pošiljka će biti spremna za isporuku nakon vidljive uplate na našem računu.</p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style='padding: 30px 30px 30px 30px; background-color: #9680af'>
                                    <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                        <tr>
                                            <td style='color: #ffffff; font-family: Arial, sans-serif; font-size: 14px; text-align: center' width='100%'>
                                                    &reg; uncasual.design " . date('Y') . "<br/>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>";

        return mail($user->getEmail(), $email_subject, $email_message, $mailHeaders);
    }
}
