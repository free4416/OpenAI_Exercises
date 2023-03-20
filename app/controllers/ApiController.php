<?php

require_once 'app/models/ApiModel.php';

class ApiController {
    private $model;

    public function __construct() {
        $this->model = new ApiModel();
    }

    public function processVideo($video_url) {
        $audio_file = $this->model->downloadAudio($video_url);
        $transcribed_text = $this->model->transcribeAudio($audio_file);
        $outline = $this->model->generateOutline($transcribed_text);

        // Clean up temporary audio file
        unlink($audio_file);

        return array(
            "transcribed_text" => $transcribed_text,
            "outline" => $outline
        );
    }
}
