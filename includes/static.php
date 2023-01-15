<?php 
require_once __DIR__.'/../core/config.inc.php';

$NOME_SITE="Portal Tocatoca";

$projetos=[
    [
        "nome"=>"Lorem 1",
        "content"=>[
            T(__("What is Lorem Ipsum?"))->h1()->tag("h1"),
            T(__("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.")),
            T(__("Why do we use it?"))->h1()->tag("h1")->mt(4),
            T(__("It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like)."))
        ]
    ],[
        "nome"=>"Ipsum Gelatina",
        "content"=>[
            T(__("Why do we use it?"))->h1()->tag("h1")->mt(4),
            T(__("What is Lorem Ipsum?"))->h1()->tag("h1"),
            T(__("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.")),
            T(__("It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like)."))
        ]
    ],
];

$noticias=[
    1=>[
        "titulo"=>"lorem ipsum",
        "subtitulo"=>"i love how this is works even that is not supposed to", 
        "thumb"=>(site_url("assets/img/banners/4.jpeg")),
        "content"=>[
            T(__("What is Lorem Ipsum?"))->h1()->tag("h1"),
            T(__("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.")),
        ]
    ],
    2=>[
        "titulo"=>"ipsum lorem",
        "subtitulo"=>"i love how this is works even that is not supposed to", 
        "thumb"=>(site_url("assets/img/banners/5.png")),
        "content"=>[
            T(__("What is Lorem Ipsum?"))->h1()->tag("h1"),
            T(__("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.")),
        ]
    ],
    3=>[
        "titulo"=>"lorem and rafaelis",
        "subtitulo"=>"i love how this is works even that is not supposed to", 
        "thumb"=>(site_url("assets/img/banners/2.png")),
        "content"=>[
            T(__("What is Lorem Ipsum?"))->h1()->tag("h1"),
            T(__("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.")),
        ]
    ],
];