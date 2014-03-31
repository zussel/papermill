<?php
/**
 * Created by IntelliJ IDEA.
 * User: sascha
 * Date: 3/28/14
 * Time: 4:44 PM
 */

class PaperTest extends Slim_Framework_TestCase
{
    public function setUp()
    {
        parent::setup();

        ORM::configure('sqlite::memory:');
//        ORM::configure('sqlite:db/test_papermill.sqlite');

        setup_db();

        $this->setup_dummy_data();
    }

    public function tearDown()
    {
        $db = ORM::get_db();

        $db->exec('DROP TABLE user');
        $db->exec('DROP TABLE paper');
    }

    public function testPost_SUCCESS()
    {
        $paper = json_encode(array(
            'title' => 'Mein erstes paper',
            'author' => 'Günter Hölüp',
            'year' => 2014,
            'url' => '/path/to/file'
        ));
        
        $this->post('/paper', $paper, array('Content-Type' => 'application/json'));
        $this->assertEquals(200, $this->response->status());
    }

    public function testPost_FAILURE()
    {
        $paper = json_encode(array(
            'author' => 'Günter Hölüp',
            'year' => 2014,
            'url' => '/path/to/file'
        ));

        $this->post('/paper', $paper, array('Content-Type' => 'application/json'));
        $this->assertEquals(400, $this->response->status());
    }

    public function testGet_SUCCESS()
    {
        $this->get('/paper/1');
        $this->assertEquals(200, $this->response->status());
    }

    public function testGet_FAILURE()
    {
        $this->get('/paper/2');
        $this->assertEquals(404, $this->response->status());
    }

    private function setup_dummy_data() {
        $db = ORM::get_db();

        /*
         * insert user
         */
        $db->exec('INSERT INTO user (email, passwd) VALUES ("a@a.de", "secret")');
        /*
         * insert some paper data
         */
        $db->exec('INSERT INTO paper (year, title, author, url) VALUES (2014, "Mein erstes Paper", "Günter Öhil", "/path/to/file")') or die(print_r($db->errorInfo(), true));

    }
}
