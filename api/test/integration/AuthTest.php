<?php
/**
 * Created by PhpStorm.
 * User: sascha
 * Date: 4/8/14
 * Time: 3:07 PM
 */

class AuthTest extends Slim_Framework_TestCase
{
    public function setUp()
    {
        parent::setup();

        ORM::configure('sqlite::memory:');

        setup_db();

        $this->setup_dummy_data();
    }

    public function tearDown()
    {
        drop_db();
    }

    /*
    public function testPost_Signin_SUCCESS()
    {

    }
    */

    public function testPost_Login_SUCCESS()
    {
        $user = array(
            'email' => 'a@a.de',
            'passwd' => 'secret'
        );
        $json = json_encode($user);

        $this->post('/auth/login', $json, array('Content-Type' => 'application/json'));

        $user['id'] = '1';
        $key = 'secret';
        $jwt = $jwt = JWT::encode($user, $key);

        $token = json_decode($this->response->getBody());
        var_dump($token);

        $user = JWT::decode($token->token, $key);

        var_dump($user);

        $this->assertEquals(200, $this->response->status());
    }

    private function setup_dummy_data()
    {
        $db = ORM::get_db();

        /*
         * insert user
         */
        $db->exec('INSERT INTO user (email, passwd) VALUES ("a@a.de", "secret")');

    }
}