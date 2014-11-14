<?php
/**
 * Created by PhpStorm.
 * User: sascha
 * Date: 4/8/14
 * Time: 3:07 PM
 */

class AuthTest extends Slim_Framework_TestCase
{
    public function testPost_Signin_SUCCESS()
    {
        $user_profile = array(
            'user' => array(
                'email' => 'max@mustermann.de',
                'passwd' => 'secret'
            ),
            'profile' => array(
                'first_name' => 'Max',
                'last_name' => 'Mustermann'
            )
        );

        $this->post('/auth/signin', json_encode($user_profile));

        $this->assertEquals(200, $this->response->status());

        $data = json_decode($this->response->getBody(), true);
        $this->assertNotNull($data, $this->response->status());
        $this->assertTrue(isset($data['user']));
        $this->assertTrue(isset($data['user']['id']));
        $this->assertGreaterThan(0, $data['user']['id']);
        $this->assertTrue(isset($data['profile']));
        $this->assertTrue(isset($data['profile']['id']));
        $this->assertGreaterThan(0, $data['profile']['id']);

        $this->assertEquals($data['user']['email'], $user_profile['user']['email']);
        $this->assertEquals($data['profile']['first_name'], $user_profile['profile']['first_name']);
        $this->assertEquals($data['profile']['last_name'], $user_profile['profile']['last_name']);
    }

    public function testPost_Signin_NoEmail_FAILURE()
    {
        $user_profile = json_encode(array(
            'user' => array(
                'passwd' => 'secret'
            ),
            'profile' => array(
                'first_name' => 'Max',
                'last_name' => 'Mustermann'
            )
        ));

        $this->post('/auth/signin', $user_profile);

        $this->assertEquals(400, $this->response->status());

        $data = json_decode($this->response->getBody(), true);
        $this->assertNotNull($data, $this->response->status());
        $this->assertEquals($data['error'], 'missing user email');
    }

    public function testPost_Login_SUCCESS()
    {
        $json = json_encode(array(
            'email' => 'a@a.de',
            'passwd' => 'secret'
        ));

        $this->post('/auth/login', $json);

        $token = json_decode($this->response->getBody());

        $key = $GLOBALS['config']['jwt-secret'];
        $jwt = JWT::decode($token->token, $key);

        $this->assertEquals(1, $jwt->id);
        $this->assertEquals('papermill', $jwt->aud);
        $this->assertEquals(200, $this->response->status());
    }

    public function testPost_Login_PWD_FAILURE()
    {
        $json = json_encode(array(
            'email' => 'a@a.de',
            'passwd' => 'wrong_secret'
        ));

        $this->post('/auth/login', $json);

        json_decode($this->response->getBody());

        $this->assertEquals(400, $this->response->status());

        $data = json_decode($this->response->getBody(), true);
        $this->assertNotNull($data);
        $this->assertEquals($data['error'], 'invalid password');
    }

    public function testPost_Login_PWD_MISSING()
    {
        $json = json_encode(array(
            'email' => 'a@a.de'
        ));

        $this->post('/auth/login', $json);

        json_decode($this->response->getBody());

        $this->assertEquals(400, $this->response->status());

        $data = json_decode($this->response->getBody(), true);
        $this->assertNotNull($data, $this->response->status());
        $this->assertEquals($data['error'], 'missing password');
    }

    public function testPost_Login_EMAIL_MISSING()
    {
        $json = json_encode(array(
            'passwd' => 'secret'
        ));

        $this->post('/auth/login', $json);

        $this->assertEquals(400, $this->response->status());

        $data = json_decode($this->response->getBody(), true);
        $this->assertNotNull($data, $this->response->status());
        $this->assertEquals($data['error'], 'missing email');
    }

    public function testPost_Login_EMAIL_FAILURE()
    {
        $json = json_encode(array(
            'email' => 'wrong@email.de',
            'passwd' => 'secret'
        ));

        $this->post('/auth/login', $json);

        $this->assertEquals(400, $this->response->status());

        $data = json_decode($this->response->getBody(), true);
        $this->assertNotNull($data, $this->response->status());
        $this->assertEquals($data['error'], 'unknown user');
    }
}
