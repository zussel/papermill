<?php
/**
 * Created by IntelliJ IDEA.
 * User: sascha
 * Date: 3/28/14
 * Time: 4:44 PM
 */

class PaperTest extends Slim_Framework_TestCase
{
    protected function mockUploadFile($paper) {
        $mock = $this->getMock('PaperUpload', array('upload_file', 'paper'));
        // mock upload file function
        $mock->expects($this->once())
            ->method('upload_file')
            ->with($this->anything(), $this->anything())
            ->will($this->returnValue(true));
        // mock get paper function
        $mock->expects($this->once())
            ->method('paper')
            ->with($this->anything())
            ->will($this->returnValue($paper));
        $this->app->uploader = function($c) use ($mock) {
            return $mock;
        };
    }

    protected function prepare_header($optionalHeader) {
        $optionalHeader['HTTP_AUTHORIZATION']  = 'Bearer ' . $this->createJWTToken(1);
        return parent::prepare_header($optionalHeader);
    }

    public function setUp() {
        parent::setup();

        $_FILES = array(
            'file' => array(
                'name' => 'test.txt',
                'type' => 'text/plain',
                'size' => 542,
                'tmp_name' => __DIR__ . '/_files/source-test.txt',
                'error' => 0
            )
        );
    }

    public function testPost_SUCCESS()
    {
        $paper = json_encode(array(
            'title' => 'Mein erstes Paper',
            'year' => 2014,
            'authors' => array(),
            'tags' => array()
        ));

        $this->mockUploadFile($paper);

        $this->post('/paper', $paper, null, array(
            'HTTP_CONTENT_TYPE' => 'multipart/form-data'
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

        $this->mockUploadFile($paper);

        $this->post('/paper', $paper, null, array(
            'HTTP_CONTENT_TYPE' => 'multipart/form-data'
        ));

        $this->assertEquals(400, $this->response->status());
    }

    public function testGet_SUCCESS()
    {
        $res = $this->get('/paper/1', null, null, array(
            'Content-Type' => 'application/json',
        ));
        var_dump($res);

        $this->assertEquals(200, $this->response->status());
    }

    public function testGet_FAILURE()
    {
        $this->get('/paper/2', null, null, array(
            'Content-Type' => 'application/json'
        ));

        $this->assertEquals(404, $this->response->status());
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
