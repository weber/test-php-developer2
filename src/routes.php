<?php
// Routes

$app->get('/init', function ($req, $res, $args) {

    $this->db->exec("CREATE TABLE IF NOT EXISTS shortener (
                    id INTEGER PRIMARY KEY,
                    url TEXT,
                    short_url VARCHAR(10),
                    time INTEGER)");

    return $this->renderer->render($res, 'index.phtml', $args);
});
$app->get('/', function ($request, $response, $args) {

    $insert = "INSERT INTO messages (title, message, time)
                VALUES (:title, :message, :time)";


    /*foreach ($messages as $m) {
        // Set values to bound variables
        $title = $m['title'];
        $message = $m['message'];
        $time = $m['time'];

        // Bind parameters to statement variables
        $stmt = $this->db->prepare($insert);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':time', $time);
        var_dump('ok');
        // Execute statement
        $stmt->execute();
    }

    $sql='select * from messages';
    foreach ($this->db->query($sql) as $row) {
        print_r($row);
    }*/


    // Render index view
   // return $this->renderer->render($response, 'index.phtml', $args);
    return $this->view->render($response, 'index.phtml', [
        'name' => 'hello'//$args['name']
    ]);
});//->setName('profile');

$app->get('/api/shorted', function ($req, $res, $args) {

    $data = ['link_short' => 'http://localhost/sdrefv3345'];

    $body = $res->getBody();
    $body->write(json_encode($data));
    return $res->withHeader('Content-Type', 'application/json')->withBody($body);

});
