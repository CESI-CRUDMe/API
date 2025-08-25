<?php

namespace App\controllers;

use PDO;
use App\classes\Router;
use App\controllers\PostsController;
use App\models\Post;
use App\controllers\Controller;
use Dompdf\Dompdf; // ajout pour export PDF
use Dompdf\Options; // enable remote images

class PostsViewController extends Controller
{
    public function index()
    {
        $q = $_GET['q'] ?? null;
        $sort = $_GET['sort'] ?? 'new';
        $posts = Post::getFiltered($this->pdo, $q, $sort);
        Router::render('posts/index', ['posts' => $posts, 'q' => $q, 'sort' => $sort]);
    }

    public function show(int $id)
    {
        $post = Post::getById($this->pdo, $id);
        Router::render('posts/show', ['post' => $post]);
    }

    public function create()
    {
        Router::render('posts/create');
    }

    public function edit(int $id)
    {
        $post = Post::getById($this->pdo, $id);
        if(!$post){
            http_response_code(404);
            Router::render('posts/show', ['post' => null]);
            return;
        }
        Router::render('posts/edit', ['post' => $post]);
    }

    public function pdf(int $id)
    {
        if(!$id){ http_response_code(400); echo 'Missing id'; return; }
        $post = Post::getById($this->pdo, $id);
        if(!$post){ http_response_code(404); echo 'Post introuvable'; return; }

        $created = date('d/m/Y H:i', strtotime($post['created_at']));
        $updated = !empty($post['updated_at']) ? date('d/m/Y H:i', strtotime($post['updated_at'])) : '—';
        $price   = isset($post['price']) ? number_format($post['price'], 2, ',', ' ') . ' €' : '—';
        $latlng  = (!empty($post['latitude']) && !empty($post['longitude'])) ? $post['latitude'] . ' / ' . $post['longitude'] : '—';
        $contactName  = htmlspecialchars($post['contact_name'] ?? '—');
        $contactPhone = htmlspecialchars($post['contact_phone'] ?? '—');

        $html = '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8" />'
            . '<title>Post #' . htmlspecialchars($post['id']) . '</title>'
            . '<style>*{font-family: DejaVu Sans, sans-serif;} body{font-size:12px;color:#222;margin:24px;} h1{font-size:20px;margin:0 0 10px;} h2{font-size:14px;margin:22px 0 6px;border-bottom:1px solid #ccc;padding-bottom:4px;} table.meta{width:100%;border-collapse:collapse;margin-top:10px;} table.meta td{padding:4px 6px;border:1px solid #e2e2e2;vertical-align:top;font-size:11px;} pre{white-space:pre-wrap;word-wrap:break-word;background:#f7f7f7;border:1px solid #e2e2e2;padding:10px;border-radius:4px;font-size:11px;} .small{color:#555;font-size:11px;margin-top:30px;border-top:1px solid #eee;padding-top:8px;} .label{font-weight:600;width:140px;}</style>'
            . '</head><body>'
            . '<h1>Résumé Post #' . htmlspecialchars($post['id']) . '</h1>'
            . '<table class="meta">'
            . '<tr><td class="label">Date création</td><td>' . $created . '</td><td class="label">Dernière MAJ</td><td>' . $updated . '</td></tr>'
            . '<tr><td class="label">Prix</td><td>' . $price . '</td><td class="label">Latitude / Longitude</td><td>' . htmlspecialchars($latlng) . '</td></tr>'
            . '<tr><td class="label">Contact Nom</td><td>' . $contactName . '</td><td class="label">Contact Téléphone</td><td>' . $contactPhone . '</td></tr>'
            . '</table>'
            . '<h2>Titre</h2><pre>' . htmlspecialchars($post['title']) . '</pre>'
            . '<h2>Description</h2><pre>' . htmlspecialchars($post['content']) . '</pre>'
            . '<p class="small">Document généré le ' . date('d/m/Y H:i') . '.</p>'
            . '</body></html>';

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('post-' . $post['id'] . '.pdf', ['Attachment' => true]);
    }

    public function pdfAll()
    {
        $q = $_GET['q'] ?? null;
        $sort = $_GET['sort'] ?? 'new';
        $posts = Post::getFiltered($this->pdo, $q, $sort);

        $html = '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8" />'
            . '<title>Résumé de tous les posts</title>'
            . '<style>*{font-family: DejaVu Sans, sans-serif;} body{font-size:11px;color:#222;margin:24px;} h1{font-size:18px;margin:0 0 14px;} table{width:100%;border-collapse:collapse;font-size:10px;} th,td{border:1px solid #ccc;padding:4px 6px;text-align:left;vertical-align:top;} th{background:#f0f0f0;} .small{margin-top:18px;font-size:9px;color:#555;} .nowrap{white-space:nowrap;} .truncate{max-width:240px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;} </style>'
            . '</head><body>'
            . '<h1>Résumé de tous les posts (' . count($posts) . ')</h1>';
        if($q){ $html .= '<p><strong>Filtre recherche :</strong> ' . htmlspecialchars($q) . '</p>'; }
        $html .= '<p><strong>Tri :</strong> ' . htmlspecialchars($sort) . '</p>';
        $html .= '<table><thead><tr>'
            . '<th>ID</th><th>Titre</th><th>Description (début)</th><th>Prix</th><th>Créé</th><th>MAJ</th><th>Contact</th>'
            . '</tr></thead><tbody>';
        if(empty($posts)){
            $html .= '<tr><td colspan="7" style="text-align:center;">Aucun post</td></tr>';
        } else {
            foreach($posts as $p){
                $html .= '<tr>'
                    . '<td class="nowrap">' . htmlspecialchars($p['id']) . '</td>'
                    . '<td class="truncate">' . htmlspecialchars(mb_strimwidth($p['title'] ?? '',0,60,'…')) . '</td>'
                    . '<td class="truncate">' . htmlspecialchars(mb_strimwidth($p['content'] ?? '',0,80,'…')) . '</td>'
                    . '<td class="nowrap">' . (isset($p['price'])?number_format($p['price'],2,',',' ') . ' €':'—') . '</td>'
                    . '<td class="nowrap">' . (!empty($p['created_at'])?date('d/m/Y H:i', strtotime($p['created_at'])):'—') . '</td>'
                    . '<td class="nowrap">' . (!empty($p['updated_at'])?date('d/m/Y H:i', strtotime($p['updated_at'])):'—') . '</td>'
                    . '<td class="truncate">' . htmlspecialchars(($p['contact_name'] ?? '—') . (isset($p['contact_phone']) && $p['contact_phone']!==''? ' / ' . $p['contact_phone'] : '')) . '</td>'
                    . '</tr>';
            }
        }
        $html .= '</tbody></table>'
            . '<p class="small">Document généré le ' . date('d/m/Y H:i') . '.</p>'
            . '</body></html>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('posts-recap.pdf', ['Attachment' => true]);
    }
}