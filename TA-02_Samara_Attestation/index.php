<?php

require 'vendor/autoload.php';
use GuzzleHttp\Client;

$catApiUrl = 'https://api.thecatapi.com/v1/images/search';
$Key = 'live_mj5nBFKRYqg7Zl21G5aCeIOYer5GaQBMIxwZHaVDgQmonFj24Ln7Hm7eyA2cJiTu';

$client = new Client();

try {
    $catImages = [];
    
    for ($i = 0; $i < 4; $i++) {
        $response = $client->get($catApiUrl, [
            'headers' => [
                'X-Api-Key' => $Key,
            ],
        ]);
        
        $jsonResponse = $response->getBody();
        
        $data = json_decode($jsonResponse, true);
        
        $catImageUrl = $data[0]['url'];

        $catImages[] = $catImageUrl;
    }
} catch (Exception $e) {
    echo 'Помилка: ' . $e->getMessage();
}

echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Випадкові коти</title>
    <style>
        .container {
            text-align: center;
        }

        .title {
            font-size: 24px;
            margin-top: 20px;
        }

        .reload-button {
            margin: 20px;
            padding: 10px 20px;
            background-color: #008CBA;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .cat-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .cat-image {
            width: 300px;
            height: 300px;
            object-fit: cover;
            margin: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title">Знайшли для тебе котів</h1>
        <button id="reloadButton" class="reload-button">Хочу ще!</button>
        <div class="cat-grid" id="catGrid">
HTML;

foreach ($catImages as $catImageUrl) {
    echo '<img src="' . $catImageUrl . '" class="cat-image" alt="Random Cat">';
}

echo <<<HTML
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const catGrid = document.getElementById('catGrid');
            const reloadButton = document.getElementById('reloadButton');

            reloadButton.addEventListener('click', function () {
                loadRandomCats();
            });

            async function loadRandomCats() {
                catGrid.innerHTML = '';
                for (let i = 0; i < 4; i++) {
                    fetch('https://api.thecatapi.com/v1/images/search', {
                        headers: {
                            'X-Api-Key': 'live_mj5nBFKRYqg7Zl21G5aCeIOYer5GaQBMIxwZHaVDgQmonFj24Ln7Hm7eyA2cJiTu'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        const catImage = document.createElement('img');
                        catImage.classList.add('cat-image');
                        catImage.src = data[0].url;
                        catGrid.appendChild(catImage);
                    })
                    .catch(error => {
                        console.error('Помилка: ' + error);
                    });
                }
            }

            loadRandomCats();
        });
    </script>
</body>
</html>
HTML;
?>
