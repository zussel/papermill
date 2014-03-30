<?php
/**
 * Created by IntelliJ IDEA.
 * User: sascha
 * Date: 3/28/14
 * Time: 4:44 PM
 */

class PaperTest extends Slim_Framework_TestCase
{
    public function testPost()
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
}
