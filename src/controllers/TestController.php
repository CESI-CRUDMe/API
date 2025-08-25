<?php

namespace App\controllers;

use PDO;
use App\classes\Router;
use App\controllers\Controller;
use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

class TestController extends Controller
{
    public function index()
    {
        // Paramètre optionnel pour déclencher un envoi mail de test: ?mail=1
        if(isset($_GET['mail'])) {
            $result = $this->sendTestMail();
            header('Content-Type: application/json');
            echo json_encode($result);
            return;
        }

        // Exemple génération d'un PDF avec Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $html = '<html><head><meta charset="utf-8"><style>body{font-family: DejaVu Sans, sans-serif;}h1{color:#2c3e50;}small{color:#888}</style></head><body>'
            .'<h1>Exemple PDF - TestController</h1>'
            .'<p>Ceci est un PDF généré à '.date('d/m/Y H:i:s').'.</p>'
            .'<ul>'
            .'<li>Lib: Dompdf</li>'
            .'<li>Controller: TestController::index</li>'
            .'<li>Paramètre ?mail=1 pour tester l\'envoi email</li>'
            .'</ul>'
            .'<hr><small>CRUDMe Demo</small>'
            .'</body></html>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="test.pdf"');
        echo $dompdf->output();
    }

    private function sendTestMail(): array
    {
        if(!defined('SMTP_HOST')) {
            return ['sent' => false, 'error' => 'SMTP config missing'];
        }
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->Port = SMTP_PORT;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER;
            $mail->Password = SMTP_PASS;
            if(SMTP_SECURE){
                $mail->SMTPSecure = SMTP_SECURE; // tls ou ssl
            }
            $mail->CharSet = 'UTF-8';
            $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
            $mail->addAddress(defined('MAIL_NOTIF_TO') ? MAIL_NOTIF_TO : SMTP_FROM);
            $mail->Subject = 'Test email - TestController';
            $mail->isHTML(true);
            $mail->Body = '<p>Email de test envoyé le '.date('d/m/Y H:i:s').'</p>';
            $mail->AltBody = 'Email de test envoyé';
            $mail->send();
            return ['sent' => true];
        } catch (MailException $e) {
            return ['sent' => false, 'error' => $e->getMessage()];
        } catch (\Throwable $e) {
            return ['sent' => false, 'error' => $e->getMessage()];
        }
    }
}