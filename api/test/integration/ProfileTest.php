<?php
class ProfileTest extends Slim_Framework_TestCase
{

    protected function prepare_header($optionalHeader) {
        $optionalHeader['HTTP_AUTHORIZATION']  = 'Bearer ' . $this->createJWTToken(1);
        return parent::prepare_header($optionalHeader);
    }

    public function testPost_SUCCESS_WITH_USERID()
    {
        $profile = json_encode(array(
            'first_name' => 'Otto',
            'last_name' => 'Hagel',
            'user_id' => 0,
            'active' => 0
        ));


        json_decode($this->post('/profile', $profile));

        $this->assertEquals(200, $this->response->status());

        $data = json_decode($this->response->getBody(), true);
        $this->assertNotNull($data);
        $this->assertGreaterThan(0, $data['id']);
    }

    public function testPost_SUCCESS()
    {
        $profile = json_encode(array(
            'first_name' => 'Otto',
            'last_name' => 'Hagel',
            'active' => 0
        ));

        $this->post('/profile', $profile);

        $this->assertEquals(200, $this->response->status());

        $data = json_decode($this->response->getBody(), true);
        $this->assertNotNull($data);
        $this->assertGreaterThan(0, $data['id']);
    }

    public function testGet_SUCCESS()
    {
        $res = $this->get('/profile/1', null, null, array(
            'CONTENT_TYPE'   => 'application/json',
        ));
        $this->assertEquals(200, $this->response->status());

        $arr = json_decode($res, true);

        var_dump($arr);
    }

    public function testGet_FAILURE()
    {
        $this->get('/profile/4711', null, null, array(
            'CONTENT_TYPE'   => 'application/json',
        ));
        $this->assertEquals(404, $this->response->status());
    }

    public function testFindAll_SUCCESS()
    {
        $res = $this->get('/profile/find', null, null, array(
            'CONTENT_TYPE'   => 'application/json',
        ));
        var_dump($res);
    }

    public function testFindSome_SUCCESS()
    {
        $res = $this->get('/profile/find', null, 'term=arr', array(
            'CONTENT_TYPE'   => 'application/json',
        ));
        $arr = json_decode($res, true);

        var_dump($arr);
    }

    protected function configure_database() {
        parent::configure_database();

        $db = ORM::get_db();
        /*
         * insert some paper data
         */
        $db->exec('INSERT INTO profile (first_name, last_name, user_id, active) VALUES ("Bruce", "Willis", 0, 0)');
        $db->exec('INSERT INTO profile (first_name, last_name, user_id, active) VALUES ("Arnold", "Schwarzenegger", 0, 0)');
        $db->exec('INSERT INTO profile (first_name, last_name, user_id, active) VALUES ("Sylvester", "Stalone", 0, 0)');
        $db->exec('INSERT INTO profile (first_name, last_name, user_id, active) VALUES ("Steve", "Carrel", 0, 0)');
        $db->exec('INSERT INTO profile (first_name, last_name, user_id, active) VALUES ("Jim", "Carrey", 0, 0)');
    }
}
