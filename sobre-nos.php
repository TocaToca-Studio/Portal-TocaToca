<?php
$titulo_pagina="Sobre nós";
require __DIR__.'/includes/header.php';
require __DIR__.'/includes/footer.php';
 
$page->add([
    $header,
    PAGE_MAIN([
        DIV([
            CONTAINER([
                T(__("Sobre nós"))->h1()
            ])->py(4)
        ])->w_100()->bg_primary()->white(),
        CONTAINER()->py(5)->add([ 
            T(__("Como sua empresa começou?"))->h1()->tag("h1"),
            T(__("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.")),
            T(__("Quem são os fundadores?"))->h1()->tag("h1")->mt(4),
            T(__("It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).")),
            T(__("O que deu a você a ideia de começar seu negócio?"))->h1()->tag("h1"),
            T(__("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.")),
            T(__("Quais são os seus valores fundamentais?"))->h1()->tag("h1")->mt(4),
            T(__("It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).")),
            T(__("Qual a sua motivação?"))->h1()->tag("h1")->mt(4),
            T(__("Nenhuma informação de contato
            Isso pode ser um erro caro. No mínimo, sua página \"Sobre nós\" deve incluir um endereço de e-mail e redes sociais. Ajude seus visitantes a entrar em contato com você se tiverem dúvidas ou preocupações adicionais.
            <br>
            Isso é particularmente importante para os pequenas e médias empresas. Muitos visitantes vão querer saber mais sobre sua história, seus serviços e valores.
            <br>
            Conheça a nossa página \"Sobre nós\" para se inspirar e saber mais sobre a equipe por trás do SmartBusinessPlan!."))
        ])
    ])->pb(4),
    $footer
])->send();