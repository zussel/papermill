<?php
/**
 * Created by IntelliJ IDEA.
 * User: sascha
 * Date: 3/28/14
 * Time: 4:44 PM
 */

class PaperTest extends Slim_Framework_TestCase
{
    public function testPost_SUCCESS()
    {
        $token = $this->createJWTToken(1);

        $paper = json_encode(array(
            'title' => 'Mein erstes paper',
            'year' => 2014,
            'url' => '/path/to/file'
        ));

        $this->post('/paper', $paper, array(
            'Content-Type' => 'application/json',
            'Authorization' => $token
        ));
        $this->assertEquals(200, $this->response->status());
        $body = $this->response->body();
        var_dump($body);
    }

    public function testPost_FAILURE()
    {
        $paper = json_encode(array(
            'author' => 'Günter Hölüp',
            'url' => '/path/to/file'
        ));

        $this->post('/paper', $paper, array(
            'Content-Type' => 'application/json'
        ));

        $this->assertEquals(400, $this->response->status());
    }

    public function testGet_SUCCESS()
    {
        $this->get('/paper/1', null, array(
            'Content-Type' => 'application/json'
        ));
        $this->assertEquals(200, $this->response->status());
    }

    public function testGet_FAILURE()
    {
        $this->get('/paper/2', null, array(
            'Content-Type' => 'application/json'
        ));

//        $this->assertEquals(404, $this->response->status());
        $this->assertEquals(404, $this->app->response()->status());
    }

    protected function configure_database() {
        parent::configure_database();

        $db = ORM::get_db();
        /*
         * insert some paper data
         */
        $db->exec('INSERT INTO paper (year, title, url) VALUES (2014, "Mein erstes Paper", "/path/to/file")') or die(print_r($db->errorInfo(), true));
    }
}
