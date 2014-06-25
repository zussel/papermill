<?php
class ProfileTest extends Slim_Framework_TestCase
{
    public function testPost_SUCCESS()
    {
        $profile = json_encode(array(
            'name' => 'Otto Hagel',
            'first_name' => 'Hagel',
            'last_name' => 'Hagel',
            'user_id' => 0,
            'active' => 0
        ));

        $this->post('/profile', $profile, array('Content-Type' => 'application/json'));
        $this->assertEquals(200, $this->response->status());
    }

    public function testPost_FAILURE()
    {
        $profile = json_encode(array(
            'first_name' => 'Hagel',
            'last_name' => 'Hagel',
            'active' => 0
        ));

        $this->post('/profile', $profile, array('Content-Type' => 'application/json'));
        $this->assertEquals(400, $this->response->status());
    }

    public function testGet_SUCCESS()
    {
        $this->get('/profile/1');
        $this->assertEquals(200, $this->response->status());
    }

    public function testGet_FAILURE()
    {
        $this->get('/profile/2');
        $this->assertEquals(404, $this->response->status());
    }

    protected function configure_database() {
        parent::configure_database();

        $db = ORM::get_db();
        /*
         * insert some paper data
         */
        $db->exec('INSERT INTO profile (name, first_name, last_name, user_id, active) VALUES ("bruce", "Bruce", "Willis", 0, 0)');
    }
}
