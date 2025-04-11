<?php
 
namespace App\Controllers;
 
use App\Controllers\BaseController;
use SimpleSoftwareIO\QrCode\Generator;
 
class QrCodeGeneratorController extends BaseController
{
    public function index()
    {
        $qrcode = new Generator;
        $qrCodes = [];
        $qrCodes['simple'] = $qrcode->size(120)->generate('https://www.binaryboxtuts.com/');
        $qrCodes['changeColor'] = $qrcode->size(120)->color(255, 0, 0)->generate('https://www.binaryboxtuts.com/');
        $qrCodes['changeBgColor'] = $qrcode->size(120)->color(0, 0, 0)->backgroundColor(255, 0, 0)->generate('https://www.binaryboxtuts.com/');
          
        $qrCodes['styleDot'] = $qrcode->size(120)->color(0, 0, 0)->backgroundColor(255, 255, 255)->style('dot')->generate('https://www.binaryboxtuts.com/');
        $qrCodes['styleSquare'] = $qrcode->size(120)->color(0, 0, 0)->backgroundColor(255, 255, 255)->style('square')->generate('https://www.binaryboxtuts.com/');
        $qrCodes['styleRound'] = $qrcode->size(120)->color(0, 0, 0)->backgroundColor(255, 255, 255)->style('round')->generate('https://www.binaryboxtuts.com/');
      
        $qrCodes['withImage'] = $qrcode->size(200)->format('png')->merge('img/logo.png', .4)->generate('https://www.binaryboxtuts.com/');
            return view('qr-codes', $qrCodes);
    }
}