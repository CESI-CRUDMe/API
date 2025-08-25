<?php

namespace App\controllers;

use App\models\Post;
use App\controllers\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

class PostsController extends Controller
{
    public function index($data): void
    {
        $page = $data['page'] ?? 1;
        $limit = $data['limit'] ?? 10;
        $posts = Post::getAll($this->pdo, $page, $limit);
        echo json_encode(['posts' => $posts]);
    }

    public function show(int $id): void
    {
        $post = new Post();
        $post->id = $id;
        $post = $post->get($this->pdo);
        if(!$post){
            http_response_code(404);
            echo json_encode(['message' => 'Post not found']);
            die();
        }
        echo json_encode(['post' => $post]);
    }

    public function create(): void
    {
        $data = $_POST;
        $post = new Post();

        if(!isset($data['title']) || !isset($data['content']) || !isset($data['price']) || !isset($data['latitude']) || !isset($data['longitude']) || !isset($data['contact_name']) || !isset($data['contact_phone'])){
            http_response_code(400);
            echo json_encode(['message' => 'Missing required fields']);
            die();
        }

        $post->title = htmlspecialchars($data['title']);
        $post->content = htmlspecialchars($data['content']);
        $post->price = floatval($data['price']);
        $post->latitude = floatval($data['latitude']);
        $post->longitude = floatval($data['longitude']);
        $post->contact_name = htmlspecialchars($data['contact_name']);
        $post->contact_phone = htmlspecialchars($data['contact_phone']);
        $post->create($this->pdo);

        // Envoi email confirmation (non bloquant)
        $mailResult = $this->sendConfirmationMail($post);

        echo json_encode(['message' => 'Post created successfully', 'mail' => $mailResult]);
    }

    public function update(array $params): void
    {
        $id = $params['id'] ?? null;
        if(!$id){
            http_response_code(400);
            echo json_encode(['message' => 'Missing id parameter']);
            return;
        }
        // Récupération données PUT (x-www-form-urlencoded ou JSON)
        $raw = file_get_contents('php://input');
        $data = [];
        if(isset($_SERVER['CONTENT_TYPE']) && str_contains($_SERVER['CONTENT_TYPE'], 'application/json')){
            $data = json_decode($raw, true) ?? [];
        } else {
            parse_str($raw, $data);
        }
        if(empty($data)){
            // fallback éventuel (rare)
            $data = $_REQUEST;
        }
        $post = new Post();
        $post->id = $id;
        foreach($data as $key => $value){
            if($key === 'id') continue;
            if(property_exists($post, $key)){
                // Sanitize de base
                if(in_array($key, ['title','content','contact_name','contact_phone'])){
                    $post->$key = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                } elseif(in_array($key, ['price','latitude','longitude'])) {
                    $post->$key = is_numeric($value) ? (float)$value : null;
                } else {
                    $post->$key = $value;
                }
            }
        }
        $post->update($this->pdo);
        echo json_encode(['message' => 'Post updated successfully']);
    }

    public function delete(int $id): void
    {
        if(!$this->isAuthenticated()) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            return;
        }
        $existing = Post::getById($this->pdo, $id);
        if(!$existing){
            http_response_code(404);
            echo json_encode(['message' => 'Post not found']);
            return;
        }
        $post = new Post();
        $post->id = (int)$id;
        $post->delete($this->pdo);
        echo json_encode(['message' => 'Post deleted successfully']);
    }

    private function sendConfirmationMail(Post $post): array
    {
        // Vérifie que les constantes SMTP existent
        if(!defined('SMTP_HOST')){
            return ['sent' => false, 'error' => 'SMTP config missing'];
        }

        try {
            $mailer = new PHPMailer(true);
            // $mailer->SMTPDebug = 2; // debug si besoin
            $mailer->isSMTP();
            $mailer->Host = SMTP_HOST;
            $mailer->Port = SMTP_PORT;
            $mailer->SMTPAuth = true;
            $mailer->Username = SMTP_USER;
            $mailer->Password = SMTP_PASS;
            if(SMTP_SECURE){
                $mailer->SMTPSecure = SMTP_SECURE; // 'ssl' ou 'tls'
            }
            $mailer->CharSet = 'UTF-8';

            $from = defined('SMTP_FROM') ? SMTP_FROM : SMTP_USER;
            $fromName = defined('SMTP_FROM_NAME') ? SMTP_FROM_NAME : 'CRUDMe';
            $mailer->setFrom($from, $fromName);

            $to = defined('MAIL_NOTIF_TO') ? MAIL_NOTIF_TO : $from;
            $mailer->addAddress($to);

            // Optionnel: si on veut envoyer aussi au contact crée (si email fourni dans phone/cas futur)
            if(isset($_POST['contact_email']) && filter_var($_POST['contact_email'], FILTER_VALIDATE_EMAIL)){
                $mailer->addCC($_POST['contact_email']);
            }

            $mailer->isHTML(true);
            $mailer->Subject = 'Nouveau Post créé #' . ($post->id ?? '');
            $mailer->Body = '<h2>Nouveau Post créé</h2>'
                . '<p><strong>Titre:</strong> ' . htmlspecialchars($post->title) . '</p>'
                . '<p><strong>Description:</strong><br>' . nl2br(htmlspecialchars($post->content)) . '</p>'
                . '<p><strong>Prix:</strong> ' . number_format($post->price,2,',',' ') . ' €</p>'
                . '<p><strong>Localisation:</strong> ' . $post->latitude . ', ' . $post->longitude . '</p>'
                . '<p><strong>Contact:</strong> ' . htmlspecialchars($post->contact_name) . ' (' . htmlspecialchars($post->contact_phone) . ')</p>'
                . '<hr><small>Message automatique - ' . date('d/m/Y H:i:s') . '</small>';
            $mailer->AltBody = "Nouveau Post créé\nTitre: {$post->title}\nDescription: {$post->content}";

            $mailer->send();
            return ['sent' => true];
        } catch (MailException $e) {
            return ['sent' => false, 'error' => $e->getMessage()];
        } catch (\Throwable $e) {
            return ['sent' => false, 'error' => $e->getMessage()];
        }
    }
}