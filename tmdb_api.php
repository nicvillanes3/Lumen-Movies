
<?php

require_once 'tmdb_config.php';

header('Content-Type: application/json; charset=utf-8');

$tipo = $_GET['action'] ?? 'popular';
$idioma = 'pt-BR';

$acoes = [
    'popular' => '/movie/popular',
    'series' => '/tv/popular',
    'filmes' => '/trending/movie/week',
    'busca' => '/search/multi'
];

if (!isset($acoes[$tipo])) {
    http_response_code(400);
    echo json_encode(['erro' => 'Ação inválida.']);
    exit;
}

if (TMDB_API_KEY === 'SUA_CHAVE_V3_AQUI' && TMDB_ACCESS_TOKEN === '') {
    http_response_code(500);
    echo json_encode(['erro' => 'Configure sua credencial no tmdb_config.php.']);
    exit;
}

$url = TMDB_BASE_URL . $acoes[$tipo];

if ($tipo === 'busca') {
    $termo = trim($_GET['query'] ?? '');

    if ($termo === '') {
        http_response_code(400);
        echo json_encode(['erro' => 'Digite um título para pesquisar.']);
        exit;
    }

    $url .= '?query=' . urlencode($termo);
} else {
    $url .= '?page=' . max(1, (int)($_GET['page'] ?? 1));
}

$url .= (str_contains($url, '?') ? '&' : '?') . 'language=' . $idioma;

if (TMDB_ACCESS_TOKEN !== '') {
    $cabecalhos = [
        'Authorization: Bearer ' . TMDB_ACCESS_TOKEN,
        'Accept: application/json'
    ];
} else {
    $url .= '&api_key=' . urlencode(TMDB_API_KEY);
    $cabecalhos = ['Accept: application/json'];
}

$opcoes = [
    'http' => [
        'method' => 'GET',
        'header' => implode("\r\n", $cabecalhos),
        'ignore_errors' => true,
        'timeout' => 15
    ]
];

$resposta = @file_get_contents($url, false, stream_context_create($opcoes));

if ($resposta === false) {
    http_response_code(502);
    echo json_encode(['erro' => 'Não foi possível acessar o TMDB. Confira a internet e a configuração do PHP.']);
    exit;
}

$dados = json_decode($resposta, true);

if (!is_array($dados)) {
    http_response_code(502);
    echo json_encode(['erro' => 'A resposta do TMDB não é válida.']);
    exit;
}

if (isset($dados['success']) && $dados['success'] === false) {
    http_response_code(502);
    echo json_encode([
        'erro' => $dados['status_message'] ?? 'O TMDB recusou a solicitação.'
    ]);
    exit;
}

echo json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
