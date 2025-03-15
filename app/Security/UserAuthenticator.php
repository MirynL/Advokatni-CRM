<?php


use Nette;
use App\Entities\UserEntity;
use App\Models\UserModel;

class MyAuthenticator implements Nette\Security\Authenticator
{
	public function __construct(
		private Nette\Database\Explorer $database,
		private Nette\Security\Passwords $passwords,
        private UserModel $user
	) {
	}

	public function authenticate(string $email, string $password): UserEntity
	{

        
		$row = $this->database->table('users')
			->where('email', $email)
			->fetch();
            
		if (!$row) {
			throw new Nette\Security\AuthenticationException('Uživatel neexistuje.');
		}

		if (!$this->passwords->verify($password, $row->password)) {
			throw new Nette\Security\AuthenticationException('Neplatné heslo.');
		}
        $identity = $this->user->getUserByEmail($email);
       
		return $identity;
    }
}    