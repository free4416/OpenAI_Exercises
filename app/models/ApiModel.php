<?php

class ApiModel {
    private $openai_api_key = "OPEN_AI_API_KEY";

    public function downloadAudio($videoUrl) {
        $videoId = $this->getVideoIdFromUrl($videoUrl);
        $downloadDir = "../download/";
        $outputPath = $downloadDir . $videoId;
        $command = sprintf("yt-dlp -x --audio-format mp3 -o %s %s", escapeshellarg($outputPath), escapeshellarg($videoUrl));
        shell_exec($command);
        return $outputPath . ".mp3";
    }
    
    private function getVideoIdFromUrl($videoUrl) {
        $pattern = '/\?v=([a-zA-Z0-9_-]+)/';
        preg_match($pattern, $videoUrl, $matches);
        return $matches[1];
    }

    public function transcribeAudio($audio_file_path) {
        $token = $this->openai_api_key; // 토큰 값
        $file = $audio_file_path; // 파일 경로
        $model = 'whisper-1'; // 모델 이름
        
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/audio/transcriptions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
          'file' => new CURLfile($file),
          'model' => $model,
        ));
        
        $headers = array();
        $headers[] = 'Authorization: Bearer ' . $token;
        $headers[] = 'Content-Type: multipart/form-data';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
          echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        
        $response = json_decode($result,true);
        return $response['text'];
    }

    public function generateOutline2($text) {
        $url = "https://api.openai.com/v1/chat/completions";

        $data = [
            "model" => "gpt-3.5-turbo",
            "messages" => array(
                "role" => "user",
                "content" => "
                    Organize the following text into an outline
                    Insert \n at line breaks
                    Spacing per paragraph Space four spaces
                    : {$text}"
            )
        ];

        $options = [
            'http' => [
                'header'  => "Content-Type: application/json\r\n" .
                             "Authorization: Bearer {$this->openai_api_key}\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
            ],
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result === FALSE) {
            return null;
        }

        $response = json_decode($result, true);
        return $response['choices'][0]['text'];
    }

    public function generateOutline($text) {
        $url = "https://api.openai.com/v1/chat/completions";
        $data = array(
            "model" => "gpt-3.5-turbo",
            "messages" => array(
                array(
                    "role" => "user",
                    "content" => "Organize the following text into an outline, Insert \n at line breaks, Spacing per paragraph Space four spaces : {$text}"
                )
            )
        );
        $dataJson = json_encode($data);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer $this->openai_api_key"
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseJson = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($responseJson, true);
        return $response["choices"][0]["message"]["content"];
    }
}
