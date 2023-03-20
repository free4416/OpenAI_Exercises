<?php
require_once 'app/controllers/ApiController.php';

$controller = new ApiController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $video_url = $_POST['video_url'];
    $return = $controller->processVideo($video_url);
    $outline = $return['outline'];
    $transcribed_text = $return['transcribed_text'];
}

include 'app/views/outline.php';
