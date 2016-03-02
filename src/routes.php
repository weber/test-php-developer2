<?php
// Routes

$app->get('/', function ($req, $res, $args) {

    createDB($this);

    return $this->view->render($res, 'index.phtml', [
        'name' => 'hello'//$args['name']
    ]);
});

$app->get('/{hash}', function ($req, $res, $arg) {

    $url = getOriginalLinkByHash($this, $arg['hash']);
    return $res->withHeader('Location', $url);
});

$app->get('/api/shorted', function ($req, $res, $args) {

    $params = $req->getQueryParams();
    $url_short = urlsafe_b64encode($params['link_original']);

    setHashDB($this, $params['link_original'], $url_short);
    $link = $this->settings->get('protocol') . '://' . getHname($this->settings->get('hostname')) . '/' .$url_short;

    $data = ['result' =>  $link];
    $body = $res->getBody();
    $body->write(json_encode($data));
    return $res->withHeader('Content-Type', 'application/json')->withBody($body);
});

function urlsafe_b64encode($string)
{
    $hash = '';
    $codeset = "-=+{}[]$@!?23456789abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ";
    $data = base64_encode($string);
    $data = str_replace(array('+','/','='),array('-','_','.'), $data);
    $base = strlen($codeset);
    $n = base_convert(str_pad($data, 7, "0", STR_PAD_LEFT), 16, 10);

    while ($n > 0) {
        $hash = substr($codeset, bcmod($n, $base), 1) . $hash;
        $n = bcmul(bcdiv($n, $base), '2', 0);
    }

    return $hash ;
}


/**
 * Возвращает оригинальную ссылку по хешу(короткой ссылке)
 * @param $this
 * @param $short_url
 *
 * @return mixed
 */
function getOriginalLinkByHash($this, $short_url) {
    $sql='SELECT url FROM shortener WHERE short_url= :short_url';

    $db = $this['db']->prepare($sql);
    $db->execute([':short_url' => $short_url]);
    $result = $db->fetch(PDO::FETCH_ASSOC);
    return $result['url'];
}

/**
 * Создает  sqlite базу
 * @param $this
 */
function createDB($this) {

    $this['db']->exec("
        CREATE TABLE IF NOT EXISTS shortener (
            id INTEGER PRIMARY KEY,
            url TEXT,
            short_url VARCHAR(10)
        )
        ");
}

/**
 * Сохраняет хеш урала (короткий урл)
 * @param $this
 * @param $url
 * @param $short_url
 */
function setHashDB($this, $url, $short_url) {
    $sql = "INSERT INTO shortener (url, short_url) VALUES (:url, :short_url)";

    $db = $this['db']->prepare($sql);
    $db->bindParam(':url', $url);
    $db->bindParam(':short_url', $short_url);
    $db->execute();
}

/**
 * Возваращает имя хоста с портом если он не стандартный
 * @param $hostname
 *
 * @return string
 */
function getHname ($hostname){
    if ($_SERVER['SERVER_PORT'] != 80) {
        $hostname .= ':'.$_SERVER['SERVER_PORT'];
    }

    return $hostname;
}