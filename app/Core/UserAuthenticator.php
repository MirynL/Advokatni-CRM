namespace App\Security;

use Nette;
use Nette\Security;

class Authenticator implements Security\IAuthenticator
{
    private $userRepository;

    public function __construct(\App\Model\UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function authenticate(array $credentials): Security\Identity
    {
        [$username, $password] = $credentials;

        $user = $this->userRepository->findByUsername($username);

        if (!$user) {
            throw new Security\AuthenticationException('User not found.');
        }

        if (!password_verify($password, $user->password)) {
            throw new Security\AuthenticationException('Invalid password.');
        }

        return new Security\Identity($user->id, ['user'], ['user' => $user]);
    }

    public function getRoles()
    {
        // Role můžete načítat z databáze nebo hardcodovat
        return ['user'];
    }
}