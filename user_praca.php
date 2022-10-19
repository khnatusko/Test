<?

public function updateUsers($request, $users)
{
	foreach ($users as $user) {
		try {
			$request->validate([
                     'name' => 'required','min:10',
                     'login' => 'required','min:10',
                     'email' => 'required','min:10',
                     'password' => 'required','min:10'
                 ]);
				 
				DB::table('users')->where('id', $user['id'])->update([
					'name' => $user['name'],
					'login' => $user['login'],
					'email' => $user['email'],
					'password' => Hash::make($user['password'])
				]);
		} catch (\Throwable $e) {
			return Redirect::back()->withErrors(['error', ['We couldn\'t update user: ' . $e->getMessage()]]);
		}
	}
	return Redirect::back()->with(['success', 'All users updated.']);
}

public function storeUsers($request, $users)
{

    foreach ($users as $user) {
        try {
			 $request->validate([
                     'name' => 'required','min:10',
                     'login' => 'required','min:10',
                     'email' => 'required','min:10',
                     'password' => 'required','min:10'
                 ]);
                
				DB::table('users')->insert([
					'name' => $user['name'],
					'login' => $user['login'],
					'email' => $user['email'],
					'password' => Hash::make($user['password'])
            ]);
        } catch (\Throwable $e) {
            return Redirect::back()->withErrors(['error', ['We couldn\'t store user: ' . $e->getMessage()]]);
        }
    }
    $this->sendEmail($users);
    return Redirect::back()->with(['success', 'All users created.']);
}

private function sendEmail($users)
{
    foreach ($users as $user) {
        $message = 'Account has beed created. You can log in as <b>' . $user['login'] . '</b>';
        if ($user['email']) {
            Mail::to($user['email'])
                ->cc('support@company.com')
                ->subject('New account created')
                ->queue($message);
        }
    }
    return true;
}

?>