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

        $token = $this->login('a@a.de', 'secret');

        $p = json_decode($this->post('/profile', $profile, array(
            'Content-Type' => 'application/json',
            'Authorization'  => 'Bearer ' . $token->token)
        ));
        $this->assertEquals(200, $this->response->status());
        $this->assertTrue($p->id > 0);
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
        $token = $this->login('a@a.de', 'secret');

        $this->get('/profile/1', array('Authorization'  => 'Bearer ' . $token->token));
        $this->assertEquals(200, $this->response->status());
    }

    public function testGet_FAILURE()
    {
        $this->get('/profile/4711');
        $this->assertEquals(404, $this->response->status());
    }

    public function testFindAll_SUCCESS()
    {
        
    }

    protected function configure_database() {
        parent::configure_database();

        $db = ORM::get_db();
        /*
         * insert some paper data
         */
        $db->exec('INSERT INTO profile (name, first_name, last_name, user_id, active) VALUES ("bruce", "Bruce", "Willis", 0, 0)');
        $db->exec('INSERT INTO profile (name, first_name, last_name, user_id, active) VALUES ("arnold", "Arnold", "Schwarzenegger", 0, 0)');
        $db->exec('INSERT INTO profile (name, first_name, last_name, user_id, active) VALUES ("sly", "Sylvester", "Stalone", 0, 0)');
        $db->exec('INSERT INTO profile (name, first_name, last_name, user_id, active) VALUES ("steve", "Steve", "Carrel", 0, 0)');
        $db->exec('INSERT INTO profile (name, first_name, last_name, user_id, active) VALUES ("jim", "Jim", "Carrey", 0, 0)');
    }
}
