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
        $user_profile = json_encode(array(
            'user' => array(
                'email' => 'max@mustermann.de',
                'passwd' => 'secret'
            ),
            'profile' => array(
                'first_name' => 'Max',
                'last_name' => 'Mustermann'
            )
        ));

        $this->post('/auth/signin', $user_profile, '', array(
            'Content-Type' => 'application/json'
        ));

        $result = $this->response->getBody();
        var_dump($result);
    }

    public function testPost_Login_SUCCESS()
    {
        $token = $this->login('a@a.de', 'secret');

        $key = $GLOBALS['config']['jwt-secret'];
        $jwt = JWT::decode($token->token, $key);

        $this->assertEquals(1, $jwt->id);
        $this->assertEquals('papermill', $jwt->aud);
        $this->assertEquals(200, $this->response->status());
    }

    public function testPost_Login_PWD_FAILURE()
    {
        $this->login('a@a.de', 'wrong_secret');

        $this->assertEquals(400, $this->response->status());
    }

    public function testPost_Login_EMAIL_FAILURE()
    {
        $this->login('wrong@email.de', 'secret');

        $this->assertEquals(400, $this->response->status());
    }
}
