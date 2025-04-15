<?php


namespace PhpMailer\Controller;

use PhpMailer\Controller;
use PhpMailer\Database\User;
use PhpMailer\Insert;
use PhpMailer\Library\Snowflake\Snowflake;
use PhpMailer\Select;
use PhpMailer\View;
use PhpTraining\Database\Manufacturer;

class AuthController extends Controller
{
    public function indexAction(): void
    {

    }

    public function loginAction(): void
    {
        if (isset($_SESSION['user'])) {
            header('Location: /app');
            exit;
        }


        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];

            if (empty($username) || empty($password)) {
                $errors[] = "Please fill in all fields.";
            }

            if (empty($errors)) {
                $userModel = new User();
                $select = new Select($this->connection);
                $select->from($userModel)
                    ->where('username = :username', ['username' => $username]);

                $user = $select->fetchAll();


                if (!$user || !password_verify($password, $user[0]['password_hash'])) {
                    $errors[] = "Invalid username or password.";
                } else {

                    $userid = $user[0]['id'];


                    $_SESSION = $this->createSession($userid);

                    header('Location: /app');
                    exit;
                }
            }
        }

        $view = new View();
        echo $view->setInnerLayout('../view/login.phtml')
            ->setOuterLayout('../view/outerlayout.phtml')
            ->setTitle("Login")
            ->setData('errors', $errors)
            ->addHeadScript('/js/tailwind.js')
            ->addHeadScript("/js/tailwind.config.js")
            ->addHeadScript("/js/alpine.js")
            ->render();
    }

    private function createSession(int $userid): array
    {
        return [
            'user_id' => $userid,
            'session_created_at' => time()
        ];

    }

    public function registerAction(): void
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $flakegen = Snowflake::getInstance();
            $username = trim($_POST['username']);
            $username = preg_replace('/[^a-zA-Z0-9_.-]/', '', $username);
            $displayname = $_POST['displayname'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if (empty($displayname) || $displayname == '') {
                $displayname = $username;
            }

            if (empty($username) || empty($password)) {
                $errors[] = "Please fill in all fields.";
            }

            if (!preg_match('/^[a-zA-Z0-9_.-]+$/', $_POST['username'])) {
                $errors[] = 'Username can only contain letters, numbers, underscore (_), dot (.) and dash (-).';
            }


            if ($password !== $confirm_password) {
                $errors[] = "Passwords do not match.";
            }

            $newUser = new User();

            $select = new Select($this->connection);
            $select->from($newUser);
            $select->where('username = :username', ['username' => $username]);
            $result = $select->fetchAll();
            if (!empty($result)) {
                $errors[] = "Username is already taken.";
            }

            if (empty($errors)) {
                if (isset($_POST['submit'])) {
                    $username = $_POST['username'];
                    $snowflake = $flakegen->generate();
                    $insert = (new Insert($this->connection))
                        ->into(new User())
                        ->columns(['id', 'username', 'password_hash', 'display_name'])
                        ->values([$snowflake, $username, password_hash($_POST['password'], PASSWORD_DEFAULT), $displayname])
                        ->executeStmt();


                    /*
                                        $_SESSION['username'] = $username;
                                        $_SESSION['user_id'] = $this->connection->lastInsertId();
                                        $_SESSION['session_created_at'] = time();

                    */
                    $_SESSION = $this->createSession($snowflake);

                    header('Location: /app');
                }

            }


        }

        $view = new View();
        echo $view->setInnerLayout('../view/register.phtml')
            ->setOuterLayout('../view/outerlayout.phtml')
            ->setTitle("Registrieren")
            ->setData('errors', $errors)
            ->addHeadScript('/js/tailwind.js')
            ->addHeadScript("/js/tailwind.config.js")
            ->addHeadScript("/js/alpine.js")
            ->render();
    }

    public function logoutAction(): void
    {
        session_destroy();
        header('Location: /');
        exit;
    }

}
