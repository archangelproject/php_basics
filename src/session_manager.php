<?php
namespace Aero\ArchangelProject\PhpBasics\SessionManager;

class SessionManager {
    public function __construct() {
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function get($key) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }

        return null;
    }

    public function login($user) {
        $this->set_user($user);
    }

    public function set_user($user) {
	$this->set('user', $user);
    }

    public function get_user() {
	return $this->get('user');
    }

    public function destroy() {
        session_unset();
        session_destroy();
    }

    public function is_logged_in() {
        return isset($_SESSION['user']);
    }
}
?>
