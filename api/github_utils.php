<?php
// github_utils.php
function getFileFromGithub($owner, $repo, $filePath, $token) {
    $filePath = urlencode($filePath);
    $api_url = "https://api.github.com/repos/$owner/$repo/contents/$filePath";
    
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "User-Agent: PHP",
        "Authorization: token $token",
        "Accept: application/vnd.github.v3.raw"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpcode != 200) {
        die("Failed to get file from GitHub, HTTP code: $httpcode");
    }
    
    return $response;
}

function uploadToGithub($owner, $repo, $filePath, $content, $token) {
    $filePath = urlencode($filePath);
    $api_url = "https://api.github.com/repos/$owner/$repo/contents/$filePath";
    $data = [
        "message" => "Add file",
        "content" => base64_encode($content)
    ];
    $options = [
        "http" => [
            "header" => [
                "User-Agent: PHP",
                "Authorization: token $token",
                "Content-Type: application/json",
                "Accept: application/vnd.github.v3+json"
            ],
            "method" => "PUT",
            "content" => json_encode($data)
        ]
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($api_url, false, $context);
    if ($response === FALSE) {
        var_dump($http_response_header);
        die("Something went wrong while uploading to GitHub");
    }
}

$github_token = getenv('GITHUB_TOKEN');
$github_repo = "FYP";
$github_owner = "ruixiang0226";
?>