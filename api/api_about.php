<?php
header('Content-Type: application/json');

echo json_encode([
    "status" => true,
    "categories" => [
        [
            "name" => "Matcha",
            "type" => "Cà phê",
            "image" => "img/3.webp"
        ],
        [
            "name" => "Chè Thái Nguyên",
            "type" => "Trà",
            "image" => "img/2.webp"
        ],
        [
            "name" => "Capuchino",
            "type" => "Cà phê",
            "image" => "img/about.png"
        ]
    ],
    "testimonials" => [
        [
            "name" => "Alexander Đức",
            "content" => "Trà xanh tự nhiên, hậu ngọt"
        ],
        [
            "name" => "PGS.TS Khanh",
            "content" => "Tốt cho sức khỏe"
        ],
        [
            "name" => "Mai Cồ Việt",
            "content" => "Chất lượng cao"
        ]
    ]
], JSON_UNESCAPED_UNICODE);
